<?php

class ParagraphFactoryImage
{
    private static function getBase64($imgSrc)
    {
        // $path = 'myfolder/myimage.png';
        // $type = pathinfo($path, PATHINFO_EXTENSION);
        // $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $data = file_get_contents($imgSrc);
        return base64_encode($data);
    }

    private static function build($server, $fileSrc)
    {
        $fileName = basename($fileSrc);

        // build hal_json
        $uploadInfo = [];
        $uploadInfo[ "_links"   ] = [ "type"  => ["href" => "$server/rest/type/file/image"]];
        $uploadInfo[ "filename" ] = [["value" => $fileName]];
        $uploadInfo[ "filemime" ] = [["value" => "image/jpeg"]];
        $uploadInfo[ "uri"      ] = [["value" => "public://patrick/$fileName"]];
        $uploadInfo[ "data"     ] = [["value" => ParagraphFactoryImage::getBase64($fileSrc)]];
        $uploadInfo[ "uid"      ] = [
            [
                "target_id" => 1,
                "target_type" => "user",
                "target_uuid" => "2a843ac3-167f-4bee-9a98-728a78a539c6",
                "url" => "/thunder/user/1"
            ]
        ];

        return $uploadInfo;
    }

    private static function createFile($server, $auth, $fileSrc)
    {
        $uploadInfo = ParagraphFactoryImage::build($server, $fileSrc);

        $url = "$server/entity/file?_format=hal_json";

        $response = Curl::postHalJson($url, $auth, $uploadInfo);

        $id = $response[ "fid" ][ 0 ][ "value" ];

        return $id;
    }

    public static function createMedia($server, $auth, $fileSrc)
    {
        $url = "$server/entity/media?_format=json";
        $target_id = ParagraphFactoryImage::createFile($server, $auth, $fileSrc);

        $media = [
            "bundle" => [[
                "target_id" => "image"
            ]],
            "field_image" => [[
                "target_id" => $target_id
            ]]
        ];

        return Curl::post($url, $auth, $media);
    }

    public static function create($server, $auth, $fileSrc)
    {
        $media = ParagraphFactoryImage::createMedia($server, $auth, $fileSrc);
        $targetId = $media[ "mid" ][ 0 ][ "value" ];

        $data = [
            "type" => [[
                "target_id" => "image"
            ]],
            "field_image" => [[
                "target_id" => $targetId
            ]]
        ];

        $url = "$server/entity/paragraph?_format=json";
        $paragraph = Curl::post($url, $auth, $data);

        return [
            "media_id" => $targetId,
            "paragraph" => [
                "target_id" => $paragraph[ "id" ][ 0 ][ "value" ],
                "target_revision_id" => $paragraph[ "revision_id" ][ 0 ][ "value" ]
            ]
        ];
    }
}

?>
