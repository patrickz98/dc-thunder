<?php

include("./ThunderExportParagraphText.php");

class ThunderExportParagraph
{
    private static function getContent($nodeId)
    {
        $url = "http://localhost/thunder/entity/paragraph/$nodeId?_format=json";
        $json = Curl::get($url);
        Simple::write("zzz-content-$nodeId.json", $json);

        $type = $json[ "type" ][ 0 ][ "target_id" ];

        if (ThunderExportParagraphText::$type === $type)
        {
            return ThunderExportParagraphText::get($json);
        }

        return null;
    }

    public static function getAll($json)
    {
        foreach ($json[ "field_paragraphs" ] as $paragraph)
        {
            echo $paragraph[ "target_id" ] . "\n";
            print_r(self::getContent($paragraph[ "target_id" ]));
        }
    }
}

?>