<?php

include("./ParagraphFactoryText.php");
include("./ParagraphFactoryImage.php");

class ParagraphFactory
{
    private $server;
    private $auth;
    private $paragraphs;

    function __construct()
    {
        $this->server = Config::$thunder_server;
        $this->auth   = Config::$thunder_auth;
        $this->paragraphs = [];
    }

    public function createText($htmlBody)
    {
        $paragraph = ParagraphFactoryText::create
        (
            $this->server,
            $this->auth,
            $htmlBody
        );

        array_push($this->paragraphs, $paragraph);
    }

    public function createImage($imgSrc)
    {
        $paragraph = ParagraphFactoryImage::create
        (
            $this->server,
            $this->auth,
            $imgSrc
        );

        array_push($this->paragraphs, $paragraph);
    }

    public function build()
    {
        return $this->paragraphs;
    }
}

?>
