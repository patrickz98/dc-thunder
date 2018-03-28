<?php

class Article
{
    private static function build($paragraphs)
    {
        $time = Simple::getTimeIso();

        $struc = [];
        $struc[ "type"            ] = [["target_id" => "article"]];
        $struc[ "title"           ] = [["value" => "$time Post Example node title"]];
        $struc[ "field_seo_title" ] = [["value" => "$time Post Example seo title"]];
        $struc[ "status"          ] = [["value" => true]];
        $struc[ "field_channel"   ] = [[
            "target_id" => 1,
            "target_type" => "taxonomy_term",
            "target_uuid" => "bfc251bc-de35-467d-af44-1f7a7012b845",
            "url" => "/thunder/news"
        ]];

        $struc[ "field_paragraphs" ] = $paragraphs;

        return $struc;
    }

    public static function create($server, $paragraphs)
    {
        $url = $server . "/node?_format=json";

        $data = Article::build($paragraphs);
        $response = Curl::post($url, $data);

        return $response;
    }
}

?>
