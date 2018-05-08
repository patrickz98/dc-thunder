<?php

include("./ThunderExportParagraphText.php");
include("./ThunderExportParagraphImage.php");

class ThunderExportParagraph
{
    private static function getParagraph($target_id)
    {
        $url = Config::$thunder_server . "/entity/paragraph/$target_id?_format=json";
        $json = Curl::get($url);
        Simple::write("zzz-content-$target_id.json", $json);

        $type = $json[ "type" ][ 0 ][ "target_id" ];

        if (ThunderExportParagraphText::$type === $type)
        {
            return ThunderExportParagraphText::get($json);
        }

        if (ThunderExportParagraphImage::$type === $type)
        {
            return ThunderExportParagraphImage::get($json);
        }

        echo "STUB! --> target_id=$target_id type=$type\n";

        return null;
    }

    public static function getAll($json)
    {
        $paragraphs = [];

        foreach ($json[ "field_paragraphs" ] as $paragraphInfo)
        {
            $target_id = $paragraphInfo[ "target_id" ];
            $paragraph = self::getParagraph($target_id);

            if ($paragraph)
            {
                array_push($paragraphs, $paragraph);
            }
        }

        return $paragraphs;
    }
}

?>