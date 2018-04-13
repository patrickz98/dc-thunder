<?php

class Article
{
    private $server;
    private $auth;

    private $paragraphs;
    private $title;
    private $seoTitle;

    function __construct($thunder_server, $thunder_auth)
    {
        $this->server     = $thunder_server;
        $this->auth       = $thunder_auth;
        $this->paragraphs = new ParagraphFactory($thunder_server, $thunder_auth);
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;
    }

    public function createParagraph($paragraph)
    {
        $this->paragraphs->createParagraph($paragraph);
    }

    public function createParagraphs($paragraphs)
    {
        foreach ($paragraphs as $inx => $paragraph)
        {
            $this->createParagraph($paragraph);
        }
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
        $article[ "type"              ] = [[ "target_id" => "article" ]];
        $article[ "title"             ] = [[ "value"     => $title    ]];
        $article[ "field_seo_title"   ] = [[ "value"     => $seoTitle ]];
        $article[ "status"            ] = [[ "value"     => true      ]];
        $article[ "field_teaser_text" ] = [[ "value"     => "STUB!"   ]];
        $article[ "field_channel"     ] = [[ "target_id" => 1         ]];
        $article[ "field_paragraphs"  ] = $this->paragraphs->build();

        $imagesMediaIds = $this->paragraphs->getImagesMediaIds();

        if ($imagesMediaIds[ 0 ])
        {
            $article[ "field_teaser_media" ] = [[ "target_id" => $imagesMediaIds[ 0 ] ]];
        }

        // echo "article: " . Simple::prettyJson($article) . "\n";

        return $article;
    }

    public function post()
    {
        $url = $this->server . "/node?_format=json";
        $response = Curl::post($url, $this->auth, $this->build());

        // echo "response: " . Simple::prettyJson($response) . "\n";

        return $response;
    }
}

?>
