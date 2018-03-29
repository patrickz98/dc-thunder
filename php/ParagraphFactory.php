<?php

include("./ParagraphFactoryText.php");
include("./ParagraphFactoryImage.php");

class ParagraphFactory
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
        $paragraph = ParagraphFactoryText::create
        (
            $this->server,
            $htmlBody
        );

        array_push($this->paragraphs, $paragraph);
    }

    public function createImage($imgSrc)
    {
        $paragraph = ParagraphFactoryImage::create
        (
            $this->server,
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
