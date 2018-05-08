<?php

class ArticleParagraphFactoryImage
{
    private static function getBase64($imgSrc)
    {
        $data = file_get_contents($imgSrc);
        return base64_encode($data);
    }

    private static function build($server, $fileSrc)
    {
        $fileName = basename($fileSrc);

        // build hal_json
        return [
            "_links"   => [  "type"  => [ "href" => "$server/rest/type/file/image" ]],
            "filename" => [[ "value" => $fileName ]],
            "filemime" => [[ "value" => "image/jpeg" ]],
            "uri"      => [[ "value" => "public://patrick/$fileName" ]],
            "data"     => [[ "value" => self::getBase64($fileSrc) ]],
            "uid"      => [
                [
                    "target_id" => 1,
                    "target_type" => "user"
                ]
            ]
        ];
    }

    private static function createFile($server, $auth, $fileSrc)
    {
        $uploadInfo = self::build($server, $fileSrc);
        $url        = "$server/entity/file?_format=hal_json";
        $response   = Curl::postHalJson($url, $auth, $uploadInfo);

        return $response[ "fid" ][ 0 ][ "value" ];
    }

    public static function createMedia($server, $auth, $fileSrc)
    {
        $url = "$server/entity/media?_format=json";
        $target_id = self::createFile($server, $auth, $fileSrc);

        $media = [
            "bundle" => [
                [
                    "target_id" => "image"
                ]
            ],
            "field_image" => [
                [
                    "target_id" => $target_id
                ]
            ]
        ];

        return Curl::post($url, $auth, $media);
    }

    public static function create($server, $auth, $fileSrc)
    {
        $media    = self::createMedia($server, $auth, $fileSrc);
        $targetId = $media[ "mid" ][ 0 ][ "value" ];

        $data = [
            "type" => [
                [
                    "target_id" => "image"
                ]
            ],
            "field_image" => [
                [
                    "target_id" => $targetId
                ]
            ]
        ];

        $url = "$server/entity/paragraph?_format=json";
        $paragraph = Curl::post($url, $auth, $data);

        return [
            "media_id"  => $targetId,
            "paragraph" => [
                "target_id"          => $paragraph[ "id"          ][ 0 ][ "value" ],
                "target_revision_id" => $paragraph[ "revision_id" ][ 0 ][ "value" ]
            ]
        ];
    }
}

?>
