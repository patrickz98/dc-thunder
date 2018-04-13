<?php

class DcxExtractor
{
    private $dcx_server;
    private $dcx_auth;

    function __construct($dcx_server, $dcx_auth)
    {
        $this->dcx_server = $dcx_server;
        $this->dcx_auth   = $dcx_auth;
    }

    public function getDoc($docId)
    {
        return Curl::get($this->dcx_server . "/document/$docId", $this->dcx_auth);
    }

    public function getFile($fileId)
    {
        return Curl::get($this->dcx_server . "/file/$fileId", $this->dcx_auth);
    }

    private function getMediaConfig($attributes)
    {
        $config = Simple::parseJson($attributes[ "data-dcx_media_config" ]);
        $params = $config[ "content" ][ "params" ];

        parse_str($params, $configQuery);

        return $configQuery[ "dcx_mg_attributes_slot_value" ];
    }

    private function htmlBodyToJson($htmlBody)
    {
        $json = [];
        $xml = simplexml_load_string("<xml>$htmlBody</xml>");

        foreach($xml->children() as $dcxParagraph)
        {
            array_push($json, Simple::xmlToJson($dcxParagraph));
        }

        return $json;
    }

    public function getImages($images)
    {
        $parsedImages = [];

        if ($images)
        {
            foreach ($images as $inx => $value)
            {
                $targetImgId = $value[ "taggroup_id" ];
                $imageDocId  = $value[ "fields" ][ "DocumentRef" ][ 0 ][ "_id" ];

                // remove dcxapi:document/
                $imageDocId = substr($imageDocId, 16);
                $imageDoc   = $this->getDoc($imageDocId);
                $fileId     = $imageDoc[ "files" ][ 0 ][ "_id" ];

                // remove dcxapi:file/
                $fileId = substr($fileId, 12);

                $file = $this->getFile($fileId);

                $parsedImages[ $targetImgId ] = $file[ "properties" ][ "_file_url_absolute" ];
            }
        }

        return $parsedImages;
    }

    public function getGalleries($doc)
    {
        $galleryImgIds = $this->getImages($images = $doc[ "fields" ][ "GalleryImage" ]);

        $galleries = [];

        $dcxGallery = $doc[ "fields" ][ "Gallery" ];

        if ($dcxGallery)
        {
            foreach ($dcxGallery as $inx => $gallery)
            {
                $targetId = $gallery[ "taggroup_id" ];
                $galleries[ $targetId ] = [];

                foreach ($gallery[ "fields" ][ "TagGroupRef" ] as $iny => $img)
                {
                    $imgRef = $img[ "ref_taggroup_id" ];
                    $imgSrc = $galleryImgIds[ $imgRef ];
                    array_push($galleries[ $targetId ], $imgSrc);
                }
            }
        }

        return $galleries;
    }

    private function extractData($dcxParagraph, $imageIds, $galleries)
    {
        $attributes = $dcxParagraph[ "@attributes" ];

        if ((! $attributes) && $dcxParagraph[ "0" ])
        {
            return [
                "type" => "text",
                "text" => $dcxParagraph[ "0" ]
            ];
        }

        $type = $attributes[ "data-dcx_media_type" ];

        if ($attributes[ "class" ] === "bodytext")
        {
            return [
                "type" => "text",
                "text"  => $dcxParagraph[ "0" ]
            ];
        }

        if ($attributes[ "class" ] === "title")
        {
            // STUB!
            return null;
        }

        if ($type === "imagegroups")
        {
            return [
                "type" => "image",
                "src"  => $imageIds[ $this->getMediaConfig($attributes) ]
            ];
        }

        if ($type === "twitter")
        {
            return [
                "type" => "tweet",
                "src"  => $this->getMediaConfig($attributes)
            ];
        }

        if ($type === "youtube")
        {
            return [
                "type" => "youtube",
                "src"  => $this->getMediaConfig($attributes)
            ];
        }

        if ($type === "gallery")
        {
            return [
                "type"   => "gallery",
                "images" => $galleries[ $this->getMediaConfig($attributes) ]
            ];
        }

        if (count($dcxParagraph) > 0)
        {
            echo "--> unknown paragraph: " . Simple::prettyJson($dcxParagraph) . "\n";
        }

        return null;
    }

    public function getStory($docId)
    {
        // $getDoc  = function($docId)  { return $this->getDoc($docId);   };
        // $getFile = function($fileId) { return $this->getFile($fileId); };

        $doc = $this->getDoc($docId);

        if ($doc[ "status" ] >= 400)
        {
            echo "error\n";
            echo Simple::prettyJson($doc) . "\n";

            return null;
        }

        $galleries = $this->getGalleries($doc);
        $imageIds  = $this->getImages($doc[ "fields" ][ "Image" ]);

        $htmlBody   = $doc[ "fields" ][ "body" ][ 0 ][ "value" ];
        $paragraphs = [];

        foreach($this->htmlBodyToJson($htmlBody) as $dcxParagraph)
        {
            $paragraph = $this->extractData($dcxParagraph, $imageIds, $galleries);

            if ($paragraph)
            {
                array_push($paragraphs, $paragraph);
            }
        }

        $story = [
            "title"         => strip_tags($doc[ "fields" ][ "Title"          ][ 0 ][ "value" ]),
            "headline"      => strip_tags($doc[ "fields" ][ "Headline"       ][ 0 ][ "value" ]),
            "sub_headline"  => strip_tags($doc[ "fields" ][ "SubHeadline"    ][ 0 ][ "value" ]),
            "display_title" => strip_tags($doc[ "fields" ][ "_display_title" ][ 0 ][ "value" ]),
            "teaser_text"   => strip_tags($doc[ "fields" ][ "Highline"       ][ 0 ][ "value" ]),
            "paragraphs"    => $paragraphs
        ];

        return $story;
    }
}

?>
