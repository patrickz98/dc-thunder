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

        // UUID --> used to ensure no duplication
        // UUID dirty because it generates an server error if it already exist
        $article = [
            "uuid"              => [[ "value"     => Simple::createUUID($seoTitle) ]],
            "type"              => [[ "target_id" => "article"                     ]],
            "title"             => [[ "value"     => $title                        ]],
            "field_seo_title"   => [[ "value"     => $seoTitle                     ]],
            "status"            => [[ "value"     => true                          ]],
            "field_teaser_text" => [[ "value"     => $teaserText                   ]],
            "field_channel"     => [[ "target_id" => 1                             ]],
            "field_meta_tags"   => [[ "value"     => serialize($this->metaTags)    ]],
            "field_paragraphs"  => $this->paragraphs->build()
        ];

        $imagesMediaIds = $this->paragraphs->getImageMediaIds();

        if ($imagesMediaIds[ 0 ])
        {
            $article[ "field_teaser_media" ] = [[ "target_id" => $imagesMediaIds[ 0 ] ]];
        }

        // Simple::logJson("article", $article);
        // Simple::write("zzz-post.json", $article);

        return $article;
    }

    public function post()
    {
        $url = $this->server . "/node?_format=json";
        $response = Curl::post($url, $this->auth, $this->build());

        // See UUID dirty
        if ($response[ "XXX_Error" ])
        {
            // Simple::logJson("response", $response);
            echo "duplicate stop\n";

            return null;
        }

        $nodeId = $response[ "nid" ][ 0 ][ "value" ];

        if (! $nodeId)
        {
            echo "error\n";
            echo "See zzz-error-response.json\n";
            Simple::write("zzz-error-response.json", $response);
        }

        return $nodeId;
    }
}

?>
