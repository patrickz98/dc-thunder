<?php

include("./ParagraphsText.php");
include("./ParagraphsImage.php");

class Paragraphs
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
        $paragraph = ParagraphsText::create
        (
            $this->server,
            $htmlBody
        );

        array_push($this->paragraphs, $paragraph);
    }

    public function createImage($imgSrc)
    {
        $paragraph = ParagraphsImage::create
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
