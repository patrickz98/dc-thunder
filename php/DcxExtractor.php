<?php

include("./DcxExtractorImages.php");

class DcxExtractor
{
    private $server;
    private $auth;

    function __construct($server, $auth)
    {
        $this->server = $server;
        $this->auth = $auth;
    }

    public static function getDoc($server, $auth, $docId)
    {
        return Curl::get("$server/document/$docId", $auth);
    }

    public static function getFile($server, $auth, $fileId)
    {
        return Curl::get("$server/file/$fileId", $auth);
    }

    public function getStory($docId)
    {
        $doc = self::getDoc($this->server, $this->auth, $docId);

        $headline      = strip_tags($doc[ "fields" ][ "Headline"       ][ 0 ][ "value" ]);
        $subHeadline   = strip_tags($doc[ "fields" ][ "SubHeadline"    ][ 0 ][ "value" ]);
        $title         = strip_tags($doc[ "fields" ][ "Title"          ][ 0 ][ "value" ]);
        $display_title = strip_tags($doc[ "fields" ][ "_display_title" ][ 0 ][ "value" ]);
        $htmlBody      = $doc[ "fields" ][ "body"           ][ 0 ][ "value" ];

        $images = DcxExtractorImages::getImages($this->server, $this->auth, $doc);
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
            }
            else
            {
                $paragraph[ "type" ] = "text";
                $paragraph[ "text" ] = $json[ "0" ];
                array_push($paragraphs, $paragraph);
            }
        }

        $story = [];
        $story[ "headline"      ] = $headline;
        $story[ "subHeadline"   ] = $subHeadline;
        $story[ "title"         ] = $title;
        $story[ "display_title" ] = $display_title;
        $story[ "paragraphs"    ] = $paragraphs;
//        $story[ "htmlBody"      ] = $htmlBody;

        return $story;
    }
}

?>
