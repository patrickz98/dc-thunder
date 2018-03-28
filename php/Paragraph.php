<?php

class Paragraph
{
    public static function create($server)
    {
        $url = $server . "/entity/paragraph?_format=json";
        $randomText = Simple::getRandomText();

        $data = [
            "type" => [
                [
                    "target_id" => "text"
                ]
            ],
            "field_text" => [
                [
                    "value" => "<p>$randomText</p>\r\n",
                    "format" => "basic_html"
                ]
            ]
        ];

        $response = Curl::post($url, $data);

        return [
            "target_id" => $response[ "id" ][ 0 ][ "value" ],
            "target_revision_id" => $response[ "revision_id" ][ 0 ][ "value" ],
            "target_type" => "paragraph"
        ];
    }
}

?>
