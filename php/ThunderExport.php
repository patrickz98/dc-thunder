<?php

include("./ThunderExportParagraph.php");

class ThunderExport
{
    private static function getTitle($json)
    {
        return $json[ "title" ][ 0 ][ "value" ];
    }

    private static function getSEOTitle($json)
    {
        return $json[ "field_seo_title" ][ 0 ][ "value" ];
    }

    private static function getTeaserText($json)
    {
        return $json[ "field_teaser_text" ][ 0 ][ "value" ];
    }

    private static function getParagraphs($json)
    {
        return ThunderExportParagraph::getAll($json);
    }

    public static function exportArticle()
    {
        $url = "http://localhost/thunder/node/11?_format=json";
        $json = Curl::get($url);

        //Simple::write("zzz-content.json", $json);

        return [
            "title"      => self::getTitle($json),
            "seoTitle"   => self::getSEOTitle($json),
            "teaserText" => self::getTeaserText($json),
            "paragraphs" => self::getParagraphs($json)
        ];
    }
}

?>