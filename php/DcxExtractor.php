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

    private function extractData($dcxParagraph, $imageIds)
    {
        $attributes = $dcxParagraph[ "@attributes" ];

        if (is_null($attributes))
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

    public function getImages($doc)
    {
        $parsedImages = [];
        $images = $doc[ "fields" ][ "Image" ];

        if ($images)
        {
            foreach ($images as $inx => $value)
            {
                $targetImgId = $value[ "taggroup_id" ];
                $imageDocId = $value[ "fields" ][ "DocumentRef" ][ 0 ][ "_id" ];

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

    public function getStory($docId)
    {
        $doc = $this->getDoc($docId);

        $imageIds = $this->getImages($doc);

        $htmlBody = $doc[ "fields" ][ "body" ][ 0 ][ "value" ];
        $paragraphs = [];

        foreach($this->htmlBodyToJson($htmlBody) as $dcxParagraph)
        {
//            echo "bla: " . Simple::prettyJson($json) . "\n";
            array_push($paragraphs, $this->extractData($dcxParagraph, $imageIds));
        }

        $story = [];
        $story[ "headline"      ] = strip_tags($doc[ "fields" ][ "Headline"       ][ 0 ][ "value" ]);
        $story[ "subHeadline"   ] = strip_tags($doc[ "fields" ][ "SubHeadline"    ][ 0 ][ "value" ]);
        $story[ "title"         ] = strip_tags($doc[ "fields" ][ "Title"          ][ 0 ][ "value" ]);
        $story[ "display_title" ] = strip_tags($doc[ "fields" ][ "_display_title" ][ 0 ][ "value" ]);
        $story[ "paragraphs"    ] = $paragraphs;

        return $story;
    }
}

?>
