<?php

class ThunderExportParagraphText
{
    public static $type = "text";

    public static function get($json)
    {
        return [
            "type" => self::$type,
            "text" => $json[ "field_text" ][ 0 ][ "value" ]
        ];
    }
}

?>