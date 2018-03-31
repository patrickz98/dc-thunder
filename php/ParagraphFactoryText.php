<?php

class ParagraphFactoryText
{
    public static function create($server, $auth, $htmlBody)
    {
        $url = $server . "/entity/paragraph?_format=json";

        $data = [
            "type" => [
                [
                    "target_id" => "text"
                ]
            ],
            "field_text" => [
                [
                    "value" => $htmlBody,
                    "format" => "basic_html"
                ]
            ]
        ];

        $response = Curl::post($url, $auth, $data);

        return [
            "target_id" => $response[ "id" ][ 0 ][ "value" ],
            "target_revision_id" => $response[ "revision_id" ][ 0 ][ "value" ]
        ];
    }
}

?>
