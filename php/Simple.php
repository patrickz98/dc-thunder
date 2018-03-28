<?php

include("./SimpleRandomWords.php");

class Simple
{
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
}

?>
