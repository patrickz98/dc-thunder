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

    private static function getLangcode($json)
    {
        return $json[ "langcode" ][ 0 ][ "value" ];
    }

    private static function getParagraphs($json)
    {
        ThunderExportParagraph::getAll($json);
    }

    public static function exportArticle()
    {
        $url = "http://localhost/thunder/node/11?_format=json";
        $json = Curl::get($url);

        Simple::write("zzz-content.json", $json);

        echo self::getTitle($json) . "\n";
        echo self::getSEOTitle($json) . "\n";
        echo self::getTeaserText($json) . "\n";
        echo self::getLangcode($json) . "\n";

        self::getParagraphs($json);
    }
}

?>