<?php

include("./DcxExtractorImages.php");

class DcxExtractor
{
    public static function getDoc($docId)
    {
        return Curl::get(Config::$dcx_server . "/document/$docId", Config::$dcx_auth);
    }

    public static function getFile($fileId)
    {
        return Curl::get(Config::$dcx_server . "/file/$fileId", Config::$dcx_auth);
    }

    public static function getStory($docId)
    {
        $doc = self::getDoc($docId);

        $htmlBody = $doc[ "fields" ][ "body" ][ 0 ][ "value" ];

        $images = DcxExtractorImages::getImages($doc);
        $paragraphs = [];

        $xml = simplexml_load_string("<xml>$htmlBody</xml>");

        foreach($xml->children() as $xml)
        {
            $json = Simple::xmlToJson($xml);
            $attributes = $json[ "@attributes" ];

            $paragraph = [];

            if ($attributes)
            {
                if ($attributes[ "data-dcx_media_type" ] === "imagegroups")
                {
                    $mediaSlot = $json[ "0" ];

                    $imgId = explode("#", $mediaSlot)[ 1 ];
                    echo "--> images: " . $imgId . "\n";

                    $paragraph[ "type" ] = "image";
                    $paragraph[ "src"  ] = $images[ $imgId ];
                    array_push($paragraphs, $paragraph);
                }
                else
                {
                    echo "--> " . Simple::prettyJson($json) . "\n"
                }
            }
            else
            {
                $paragraph[ "type" ] = "text";
                $paragraph[ "text" ] = $json[ "0" ];
                array_push($paragraphs, $paragraph);
            }
        }

        $story = [];
        $story[ "headline"      ] = strip_tags($doc[ "fields" ][ "Headline"       ][ 0 ][ "value" ]);
        $story[ "subHeadline"   ] = strip_tags($doc[ "fields" ][ "SubHeadline"    ][ 0 ][ "value" ]);
        $story[ "title"         ] = strip_tags($doc[ "fields" ][ "Title"          ][ 0 ][ "value" ]);
        $story[ "display_title" ] = strip_tags($doc[ "fields" ][ "_display_title" ][ 0 ][ "value" ]);
        $story[ "paragraphs"    ] = $paragraphs;
//        $story[ "htmlBody"      ] = $htmlBody;

        return $story;
    }
}

?>
