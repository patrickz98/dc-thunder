<?php

class Article
{
    private $server;
    private $auth;

    private $paragraphs;
    private $title;
    private $seoTitle;
    private $teaserText;
    private $metaTags;

    function __construct($thunder_server, $thunder_auth)
    {
        $this->server     = $thunder_server;
        $this->auth       = $thunder_auth;
        $this->paragraphs = new ArticleParagraphFactory($thunder_server, $thunder_auth);
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;
    }

    public function setTeaserText($text)
    {
        $this->teaserText = $text;
    }

    public function setMetaTags($metaTags)
    {
        $this->metaTags = $metaTags;
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

        $title      = $this->title;
        $seoTitle   = $this->seoTitle;
        $teaserText = $this->teaserText;

        if (! $title)
        {
            $title = "Default Title: $time";
        }

        if (! $seoTitle)
        {
            $seoTitle = "Default Seo Title: $time";
        }

        if (! $teaserText)
        {
            $teaserText = $seoTitle;
        }

        $article = [];
        $article[ "type"              ] = [[ "target_id" => "article"       ]];
        $article[ "title"             ] = [[ "value"     => $title          ]];
        $article[ "field_seo_title"   ] = [[ "value"     => $seoTitle       ]];
        $article[ "status"            ] = [[ "value"     => true            ]];
        $article[ "field_teaser_text" ] = [[ "value"     => $teaserText     ]];
        $article[ "field_channel"     ] = [[ "target_id" => 1               ]];
        $article[ "metatag"           ] = [[ "value"     => $this->metaTags ]];
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
