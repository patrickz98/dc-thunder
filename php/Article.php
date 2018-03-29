<?php

class Article
{
    private $server;
    private $paragraphs;
    private $title;
    private $seoTitle;

    function __construct($server)
    {
        $this->server = $server;
        $this->paragraphs = [];
    }

    public function addParagraph($paragraph)
    {
        array_push($this->paragraphs, $paragraph);
    }

    public function addParagraphs($paragraphs)
    {
        $this->paragraphs = array_merge($this->paragraphs, $paragraphs);
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;
    }

    private function build()
    {
        $time = Simple::getTimeIso();
        // $time = Simple::getHumanTime();

        $title = $this->title;
        $seoTitle = $this->seoTitle;

        if (! $title)
        {
            $title = "Default Title: $time";
        }

        if (! $seoTitle)
        {
            $seoTitle = "Default Seo Title: $time";
        }

        $article = [];
        $article[ "type"            ] = [["target_id" => "article"]];
        $article[ "title"           ] = [["value" => $title]];
        $article[ "field_seo_title" ] = [["value" => $seoTitle]];
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

    public function post()
    {
        $url = $this->server . "/node?_format=json";
        $response = Curl::post($url, Article::build());

        return $response;
    }
}

?>
