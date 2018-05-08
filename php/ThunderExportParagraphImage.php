<?php

include("./DcxFileUpload.php");

class ThunderExportParagraphImage
{
    public static $type = "image";

    private static function getSrc($mediaJson)
    {
        $fileId   = $mediaJson[ "field_image" ][ 0 ][ "target_id" ];
        $url      = Config::$thunder_server . "/file/" . $fileId . "?_format=json";
        $fileJson = Curl::get($url);

        //Simple::write("zzz-file-$fileId.json", $fileJson);

        $src = Config::$thunder_host . $fileJson[ "url" ][ 0 ][ "value" ];
        $filename = $fileJson[ "filename" ][ 0 ][ "value" ];
        $filemime = $fileJson[ "filemime" ][ 0 ][ "value" ];

        return [
            "src"      => $src,
            "filename" => $filename,
            "filemime" => $filemime,
        ];
    }

    private static function getMedia($json)
    {
        $mediaId = $json[ "field_image" ][ 0 ][ "target_id" ];
        $url = Config::$thunder_server . "/media/" . $mediaId . "?_format=json";
        $mediaJson = Curl::get($url);

        //Simple::write("zzz-media-$mediaId.json", $mediaJson);

        return $mediaJson;
    }

    public static function get($json)
    {
        $mediaJson = self::getMedia($json);

        return [
            "type" => self::$type,
            "data" => self::getSrc($mediaJson)
        ];
    }
}

?>