<?php

include("./DcxExtractorImages.php");

class DcxExtractor
{
    private $server;

    function __construct($server)
    {
        $this->server = $server;
    }

    public static function getDoc($server, $docId)
    {
        $docUrl = "$server/document/$docId";
        $auth = "testuser:dc";

        $doc = Curl::get($docUrl, $auth);

        return $doc;
    }

    public static function getFile($server, $fileId)
    {
        $docUrl = "$server/file/$fileId";
        $auth = "testuser:dc";

        $doc = Curl::get($docUrl, $auth);

        return $doc;
    }

    public function getStory($docId)
    {
        $doc = self::getDoc($this->server, $docId);

        $headline      = strip_tags($doc[ "fields" ][ "Headline"       ][ 0 ][ "value" ]);
        $subHeadline   = strip_tags($doc[ "fields" ][ "SubHeadline"    ][ 0 ][ "value" ]);
        $title         = strip_tags($doc[ "fields" ][ "Title"          ][ 0 ][ "value" ]);
        $display_title = strip_tags($doc[ "fields" ][ "_display_title" ][ 0 ][ "value" ]);
        $htmlBody      = $doc[ "fields" ][ "body"           ][ 0 ][ "value" ];

        $images = DcxExtractorImages::getImages($this->server, $doc);
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
