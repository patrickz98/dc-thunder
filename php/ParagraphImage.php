<?php

class ParagraphImage
{
    private static function gatBase64($imgSrc)
    {
        // $path = 'myfolder/myimage.png';
        // $type = pathinfo($path, PATHINFO_EXTENSION);
        // $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $data = file_get_contents($imgSrc);
        return base64_encode($data);
    }

    private static function build($server, $fileName)
    {
        // build hal_json
        $uploadInfo = [];
        $uploadInfo[ "_links"   ] = [ "type"  => ["href" => "$server/rest/type/file/image"]];
        $uploadInfo[ "filename" ] = [["value" => $fileName]];
        $uploadInfo[ "filemime" ] = [["value" => "image/jpeg"]];
        $uploadInfo[ "uri"      ] = [["value" => "public://patrick/$fileName"]];
        $uploadInfo[ "uid"      ] = [
            [
                "target_id" => 1,
                "target_type" => "user",
                "target_uuid" => "2a843ac3-167f-4bee-9a98-728a78a539c6",
                "url" => "/thunder/user/1"
            ]
        ];

        $uploadInfo[ "data" ] = [["value" => ParagraphImage::gatBase64($fileName)]];

        return $uploadInfo;
    }

    public static function createFile($server, $fileName)
    {
        $uploadInfo = ParagraphImage::build($server, $fileName);

        $url = "$server/entity/file?_format=hal_json";

        $response = Curl::postHalJson($url, $uploadInfo);

        $id = $response[ "fid" ][ 0 ][ "value" ];

        return $id;
    }

    public static function createMedia($server, $fileName)
    {
        $url = "$server/entity/media?_format=json";
        $target_id = ParagraphImage::createFile($server, $fileName);

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

        return Curl::post($url, $media);
    }

    public static function create($server, $fileName)
    {
        return ParagraphImage::createMedia($server, $fileName);
    }
}

?>
