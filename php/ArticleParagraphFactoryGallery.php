<?php

class ArticleParagraphFactoryGallery extends ArticleParagraphFactoryImage
{
    private static function createGalleryMedia($server, $auth, $imagesSrc)
    {
        $imagesIds = [];

        foreach ($imagesSrc as $inx => $imageSrc)
        {
            $media = self::createMedia($server, $auth, $imageSrc);
            $targetId = $media[ "mid" ][ 0 ][ "value" ];

            array_push($imagesIds, [ "target_id" => $targetId ]);
        }

        $url = "$server/entity/media?_format=json";

        $media = [
            "bundle" => [
                [
                    "target_id" => "gallery"
                ]
            ],
            "field_media_images" => $imagesIds
        ];

        return Curl::post($url, $auth, $media);
    }


    public static function createGallery($server, $auth, $imagesSrc)
    {
        $media = self::createGalleryMedia($server, $auth, $imagesSrc);
        $targetId = $media[ "mid" ][ 0 ][ "value" ];

        $data = [
            "type" => [
                [
                    "target_id" => "gallery"
                ]
            ],
            "field_media" => [
                [
                    "target_id" => $targetId
                ]
            ]
        ];

        $url = "$server/entity/paragraph?_format=json";
        $paragraph = Curl::post($url, $auth, $data);

        return [
            "target_id"          => $paragraph[ "id"          ][ 0 ][ "value" ],
            "target_revision_id" => $paragraph[ "revision_id" ][ 0 ][ "value" ]
        ];
    }
}

?>