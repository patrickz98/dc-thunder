<?php

include("./ParagraphText.php");
include("./ParagraphImage.php");

class Article
{
    private $server;
    private $paragraphs;

    function __construct($server)
    {
        $this->server = $server;
        $this->paragraphs = [];
    }

    public function createText($htmlBody)
    {
        $paragraph = ParagraphText::create
        (
            $this->server,
            $htmlBody
        );

        array_push($this->paragraphs, $paragraph);
    }

    public function createImage($imgSrc)
    {
        $paragraph = ParagraphImage::create
        (
            $this->server,
            $imgSrc
        );

        array_push($this->paragraphs, $paragraph);
    }

    private function build()
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

        $article[ "field_paragraphs" ] = $this->paragraphs;

        return $article;
    }

    public function create()
    {
        $url = $this->server . "/node?_format=json";
        $response = Curl::post($url, Article::build());

        return $response;
    }
}

?>
