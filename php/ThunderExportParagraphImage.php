<?php

class ThunderExportParagraphImage
{
    public static $type = "image";

    private static function getSrc($mediaJson)
    {
        $fileId   = $mediaJson[ "field_image" ][ 0 ][ "target_id" ];
        $url      = Config::$thunder_server . "/file/" . $fileId . "?_format=json";
        $fileJson = Curl::get($url);

        Simple::write("zzz-file-$fileId.json", $fileJson);

        return Config::$thunder_host . $fileJson[ "url" ][ 0 ][ "value" ];
    }

    private static function getMedia($json)
    {
        $mediaId = $json[ "field_image" ][ 0 ][ "target_id" ];
        $url = Config::$thunder_server . "/media/" . $mediaId . "?_format=json";
        $mediaJson = Curl::get($url);

        Simple::write("zzz-media-$mediaId.json", $mediaJson);

        return $mediaJson;
    }

    public static function get($json)
    {
        $mediaJson = self::getMedia($json);

        return [
            "type" => self::$type,
            "src"  => self::getSrc($mediaJson)
        ];
    }
}

?>