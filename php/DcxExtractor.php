<?php

include("./DcxExtractorMetaTags.php");
include("./DcxExtractorParagraphs.php");

class DcxExtractor
{
    private static $dcx_server;
    private static $dcx_auth;

    function __construct($dcx_server, $dcx_auth)
    {
        self::$dcx_server = $dcx_server;
        self::$dcx_auth   = $dcx_auth;
    }

    public static function getDoc($docId)
    {
        return Curl::get(self::$dcx_server . "/document/$docId", self::$dcx_auth);
    }

    public static function getFile($fileId)
    {
        return Curl::get(self::$dcx_server . "/file/$fileId", self::$dcx_auth);
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

        $dcxExtractorParagraphs = new DcxExtractorParagraphs($doc);
        $paragraphs = $dcxExtractorParagraphs->getParagraphs();

        $story = [
            "uuid"          => Simple::createUUID($docId),
            "title"         => Simple::cleanHtml($doc[ "fields" ][ "Title"          ][ 0 ][ "value" ]),
            "headline"      => Simple::cleanHtml($doc[ "fields" ][ "Headline"       ][ 0 ][ "value" ]),
            "sub_headline"  => Simple::cleanHtml($doc[ "fields" ][ "SubHeadline"    ][ 0 ][ "value" ]),
            "display_title" => Simple::cleanHtml($doc[ "fields" ][ "_display_title" ][ 0 ][ "value" ]),
            "teaser_text"   => Simple::cleanHtml($doc[ "fields" ][ "Highline"       ][ 0 ][ "value" ]),
            "metatags"      => DcxExtractorMetaTags::getMetaTags($doc),
            "paragraphs"    => $paragraphs
        ];

        @mkdir("tmp");
        Simple::write("tmp/$docId.json", $doc);
        Simple::write("tmp/$docId-story-tmp.json", $story);

        if (sizeof($paragraphs) == 0)
        {
            echo "content empty\n";
            return null;
        }

//        Simple::write("zzz-dcx-doc-tmp.json",   $doc);
//        Simple::write("zzz-dcx-story-tmp.json", $story);
//        exit(0);

        return $story;
    }
}

?>
