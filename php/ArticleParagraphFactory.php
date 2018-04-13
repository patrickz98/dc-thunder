<?php

include("./ArticleParagraphFactoryText.php");
include("./ArticleParagraphFactoryImage.php");
include("./ArticleParagraphFactoryTweet.php");
include("./ArticleParagraphFactoryYoutube.php");
include("./ArticleParagraphFactoryGallery.php");

class ArticleParagraphFactory
{
    private $server;
    private $auth;
    private $paragraphs;
    private $imagesMediaIds;

    function __construct($thunder_server, $thunder_auth)
    {
        $this->server         = $thunder_server;
        $this->auth           = $thunder_auth;
        $this->paragraphs     = [];
        $this->imagesMediaIds = [];
    }

    public function createText($htmlBody)
    {
        $paragraph = ArticleParagraphFactoryText::create
        (
            $this->server,
            $this->auth,
            $htmlBody
        );

        array_push($this->paragraphs, $paragraph);
    }

    public function createImage($imgSrc)
    {
        $paragraph = ArticleParagraphFactoryImage::create
        (
            $this->server,
            $this->auth,
            $imgSrc
        );

        array_push($this->paragraphs,     $paragraph[ "paragraph" ]);
        array_push($this->imagesMediaIds, $paragraph[ "media_id"  ]);
    }

    public function createTweet($tweet)
    {
        $paragraph = ArticleParagraphFactoryTweet::create
        (
            $this->server,
            $this->auth,
            $tweet
        );

        array_push($this->paragraphs, $paragraph);
    }

    public function createYoutube($tweet)
    {
        $paragraph = ArticleParagraphFactoryYoutube::create
        (
            $this->server,
            $this->auth,
            $tweet
        );

        array_push($this->paragraphs, $paragraph);
    }

    public function createGallery($imagesSrc)
    {
        $paragraph = ArticleParagraphFactoryGallery::createGallery
        (
            $this->server,
            $this->auth,
            $imagesSrc
        );

        array_push($this->paragraphs, $paragraph);
    }

    public function createParagraph($paragraph)
    {
        $type = $paragraph[ "type" ];

        if ($type === "text")
        {
            $this->createText($paragraph[ "text" ]);
        }

        if ($type === "image")
        {
            $this->createImage($paragraph[ "src" ]);
        }

        if ($type === "tweet")
        {
            $this->createTweet($paragraph[ "src" ]);
        }

        if ($type === "youtube")
        {
            $this->createYoutube($paragraph[ "src" ]);
        }

        if ($type === "gallery")
        {
            $this->createGallery($paragraph[ "images" ]);
        }
    }

    public function getImagesMediaIds()
    {
        return $this->imagesMediaIds;
    }

    public function build()
    {
        return $this->paragraphs;
    }
}

?>
