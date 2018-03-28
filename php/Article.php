<?php

class Article
{
    private static function build($paragraphs)
    {
        // $time = Simple::getTimeIso();
        $time = Simple::getHumanTime();

        $article = [];
        $article[ "type"            ] = [["target_id" => "article"]];
        $article[ "title"           ] = [["value" => "$time --> Post Example node title"]];
        $article[ "field_seo_title" ] = [["value" => "$time --> Post Example seo title"]];
        $article[ "status"          ] = [["value" => true]];
        $article[ "field_channel"   ] = [[
            "target_id"   => 1,
            "target_type" => "taxonomy_term",
            "target_uuid" => "bfc251bc-de35-467d-af44-1f7a7012b845",
            "url"         => "/thunder/news"
        ]];

        $article[ "field_paragraphs" ] = $paragraphs;

        return $article;
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
