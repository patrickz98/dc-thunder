<?php

include("./DcxExtractorMetaTags.php");

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

    private function transformParagraph($dcxParagraph, $imageIds, $galleries)
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

    private function getParagraphs($doc)
    {
        $galleries  = $this->getGalleries($doc);
        $imageIds   = $this->getImages($doc[ "fields" ][ "Image" ]);
        $body       = $doc[ "fields" ][ "body" ];

        $paragraphs = [];

        foreach ($body as $bodyPart)
        {
            $content = $bodyPart[ "value" ];
            // echo substr($content, 0, 40) . "\n";

            if (substr($content, 0, 1) === "<")
            {
                // echo "Process as HTML\n";
                foreach($this->htmlBodyToJson($body) as $dcxParagraph)
                {
                    $paragraph = $this->transformParagraph($dcxParagraph, $imageIds, $galleries);

                    if ($paragraph)
                    {
                        array_push($paragraphs, $paragraph);
                    }
                }
            }
            else
            {
                // echo "Process as Text\n";
                // echo $content . "\n";

                $htmlText = "";

                foreach (explode("\n", $content) as $pTag)
                {
                    $htmlText .= "<p>$pTag</p>";
                }

                array_push($paragraphs, [
                    "type" => "text",
                    "text" => $htmlText
                ]);
            }
        }

        return $paragraphs;
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

        $story = [
            "title"         => Simple::cleanHtml($doc[ "fields" ][ "Title"          ][ 0 ][ "value" ]),
            "headline"      => Simple::cleanHtml($doc[ "fields" ][ "Headline"       ][ 0 ][ "value" ]),
            "sub_headline"  => Simple::cleanHtml($doc[ "fields" ][ "SubHeadline"    ][ 0 ][ "value" ]),
            "display_title" => Simple::cleanHtml($doc[ "fields" ][ "_display_title" ][ 0 ][ "value" ]),
            "teaser_text"   => Simple::cleanHtml($doc[ "fields" ][ "Highline"       ][ 0 ][ "value" ]),
            "metatags"      => DcxExtractorMetaTags::getMetaTags($doc),
            "paragraphs"    => self::getParagraphs($doc)
        ];

        Simple::write("zzz-dcx-doc-tmp.json",   $doc);
        Simple::write("zzz-dcx-story-tmp.json", $story);

//        exit(0);

        return $story;
    }
}

?>
