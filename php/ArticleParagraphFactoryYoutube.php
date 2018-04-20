<?php

class ArticleParagraphFactoryYoutube
{
    private static function createMedia($server, $auth, $youtubeUrl)
    {
        $url = "$server/entity/media?_format=json";

        $media = [
            "bundle" => [
                [
                    "target_id" => "video"
                ]
            ],
            "field_media_video_embed_field" => [
                [
                    "value" => $youtubeUrl
                ]
            ]
        ];

        return Curl::post($url, $auth, $media);
    }

    public static function create($server, $auth, $tweet)
    {
        $media = self::createMedia($server, $auth, $tweet);
        $targetId = $media[ "mid" ][ 0 ][ "value" ];

        $data = [
            "type" => [
                [
                    "target_id" => "video"
                ]
            ],
            "field_video" => [
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
