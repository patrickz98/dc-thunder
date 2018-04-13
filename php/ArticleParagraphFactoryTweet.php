<?php

class ArticleParagraphFactoryTweet
{
    private static function createMedia($server, $auth, $tweet)
    {
        $url = "$server/entity/media?_format=json";

        $media = [
            "bundle" => [[
                "target_id" => "twitter"
            ]],
            "field_url" => [[
                "uri" => $tweet
            ]]
        ];

        return Curl::post($url, $auth, $media);
    }

    public static function create($server, $auth, $tweet)
    {
        $media = self::createMedia($server, $auth, $tweet);
        $targetId = $media[ "mid" ][ 0 ][ "value" ];

        $data = [
            "type" => [[
                "target_id" => "twitter"
            ]],
            "field_media" => [[
                "target_id" => $targetId
            ]]
        ];

        $url = "$server/entity/paragraph?_format=json";
        $paragraph = Curl::post($url, $auth, $data);

        return [
            "target_id" => $paragraph[ "id" ][ 0 ][ "value" ],
            "target_revision_id" => $paragraph[ "revision_id" ][ 0 ][ "value" ]
        ];
    }
}

?>
