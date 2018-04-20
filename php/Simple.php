<?php

include("./SimpleRandomWords.php");

class Simple
{
    public static function getHumanTime()
    {
        return date("H:i:s");
    }

    public static function getTimeIso()
    {
        return date(DATE_ISO8601);
    }

    public static function parseJson($str)
    {
        return json_decode($str, true);
    }

    public static function prettyJson($array)
    {
        return json_encode($array, JSON_PRETTY_PRINT);
    }

    public static function xmlToJson($xml)
    {
        return Simple::parseJson(Simple::prettyJson($xml));
    }

    public static function getRandomText($words = null)
    {
        return SimpleRandomWords::createText($words);
    }

    public static function write($file, $array)
    {
        $ofile = @fopen($file, "w");
        @fwrite($ofile, Simple::prettyJson($array));
        @fclose($ofile);
    }

    public static function logJson($msg, $json)
    {
        echo "$msg: " . self::prettyJson($json) . "\n";
    }

    public static function cleanHtml($txt)
    {
        return html_entity_decode(strip_tags($txt));
    }

    public static function createUUID($str)
    {
        $md5 = md5($str);

        return "" .
            substr($md5, 0,  8) . "-" .
            substr($md5, 8,  4) . "-" .
            substr($md5, 12, 4) . "-" .
            substr($md5, 16, 4) . "-" .
            substr($md5, 20);
    }
}

?>
