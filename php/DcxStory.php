<?php

include("./DcxStoryBody.php");
include("./DcxStoryBodyImage.php");

class DcxStory
{
    private $server;
    private $auth;

    private $title;
    private $seoTitle;
    private $paragraphs;

    function __construct($dcx_server, $dcx_auth)
    {
        $this->server = $dcx_server;
        $this->auth   = $dcx_auth;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;
    }

    public function setParagraphs($paragraphs)
    {
        $this->paragraphs = $paragraphs;
    }

    public function buildSimpleValue($value)
    {
        return [
            [
                "position" => "1",
                "value"    => $value
            ],
        ];
    }

    private function buildStoryType()
    {
        return [
            [
                "_id"   => "dcxapi:tm_topic\/storytype-online",
                "_type" => "dcx:tm_topic",
                "value" => "Online"
            ]
        ];
    }

    private function buildFields()
    {
        $imagesBuilder = new DcxStoryBodyImage($this->server, $this->auth);
        $imagesBuilder->setSource($this->paragraphs);

        $images = $imagesBuilder->build();

        return [
            "Title"       => self::buildSimpleValue($this->title),
            "Headline"    => self::buildSimpleValue($this->title),
            "SubHeadline" => self::buildSimpleValue($this->seoTitle),
            "Catchline"   => self::buildSimpleValue($this->seoTitle),
            "StoryType"   => self::buildStoryType(),
            "body"        => DcxStoryBody::build($this->paragraphs),
        ];
    }

    private function buildProperties()
    {
        return [
            "pool_id" => [
                "_id" => "dcxapi:pool\/story",
                "_type" => "dcx:pool"
            ]
        ];
    }

    public function build()
    {
        return [
            "_type"      => "dcx:document",
            "fields"     => self::buildFields(),
            "properties" => self::buildProperties()
        ];
    }
}

?>