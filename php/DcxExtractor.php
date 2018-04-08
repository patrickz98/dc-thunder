<?php

class DcxExtractor
{
    private $dcx_server;
    private $dcx_auth;
    private $imagesTmp;

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

    private function extractData($dcxParagraph, $imageIds, $galleries)
    {
        $attributes = $dcxParagraph[ "@attributes" ];

        if (is_null($attributes) && $dcxParagraph[ "0" ])
        {
            return [
                "type" => "text",
                "src" => $dcxParagraph[ "0" ]
            ];
        }

        if ($attributes[ "data-dcx_media_type" ] === "imagegroups")
        {
            $mediaSlot = $dcxParagraph[ "0" ];
            $imgId = explode("#", $mediaSlot)[ 1 ];

            return [
                "type" => "image",
                "src" => $imageIds[ $imgId ]
            ];
        }

        if ($attributes[ "data-dcx_media_type" ] === "twitter")
        {
            $mediaSlot = $dcxParagraph[ "0" ];

            // remove MediaSlot: Tweet
            $tweetUrl = "https://" . substr($mediaSlot, 17);

            return [
                "type" => "tweet",
                "src" => $tweetUrl
            ];
        }

        if ($attributes[ "data-dcx_media_type" ] === "youtube")
        {
            $config = Simple::parseJson($attributes[ "data-dcx_media_config" ]);
            $params = $config[ "content" ][ "params" ];

            // remove dcx_mg_attributes_slot_value=
            $youtubeUrl = urldecode(substr($params, 29));

            return [
                "type" => "youtube",
                "src" => $youtubeUrl
            ];
        }

        if ($attributes[ "data-dcx_media_type" ] === "gallery")
        {
            echo "dcxParagraph=" . Simple::prettyJson($dcxParagraph) . "\n";

            $config = Simple::parseJson($attributes[ "data-dcx_media_config" ]);
            $params = $config[ "content" ][ "params" ];

            // remove dcx_mg_attributes_slot_value=
            $galleryId = urldecode(substr($params, 29));

            return [
                "type" => "gallery",
                "gallery" => $galleries[ $galleryId ]
            ];
        }

        return null;
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

                // dcxapi:file/
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

        echo "galleries=" . Simple::prettyJson($galleries) . "\n";

        return $galleries;
    }

    public function getStory($docId)
    {
        $doc = $this->getDoc($docId);

        $galleries = $this->getGalleries($doc);
        $imageIds  = $this->getImages($doc[ "fields" ][ "Image" ]);

        $htmlBody = $doc[ "fields" ][ "body" ][ 0 ][ "value" ];
        $paragraphs = [];

        foreach($this->htmlBodyToJson($htmlBody) as $dcxParagraph)
        {
            array_push($paragraphs, $this->extractData($dcxParagraph, $imageIds, $galleries));
        }

        $story = [];
        $story[ "headline"      ] = strip_tags($doc[ "fields" ][ "Headline"       ][ 0 ][ "value" ]);
        $story[ "subHeadline"   ] = strip_tags($doc[ "fields" ][ "SubHeadline"    ][ 0 ][ "value" ]);
        $story[ "title"         ] = strip_tags($doc[ "fields" ][ "Title"          ][ 0 ][ "value" ]);
        $story[ "display_title" ] = strip_tags($doc[ "fields" ][ "_display_title" ][ 0 ][ "value" ]);
        $story[ "paragraphs"    ] = $paragraphs;

        echo "paragraphs=" . Simple::prettyJson($paragraphs) . "\n";

        return $story;
    }
}

?>
