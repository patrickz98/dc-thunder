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

    private function buildType()
    {
        return [
            [
                "_id"   => "dcxapi:tm_topic/documenttype-story",
                "_type" => "dcx:tm_topic",
                "value" => "Story"
            ]
        ];
    }

    private function buildStoryType()
    {
        return [
            [
                "_id"   => "dcxapi:tm_topic/storytype-online",
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
            "Type"        => self::buildType(),
            "StoryType"   => self::buildStoryType(),
            "body"        => DcxStoryBody::build($this->paragraphs),
        ];
    }

    private function buildProperties()
    {
        return [
            "pool_id" => [
                "_id" => "dcxapi:pool/story",
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

    public function post()
    {
        // http://192.168.18.131/dcx/api
        $url = Config::$dcx_server . "/document";

        return Curl::post($url, Config::$dcx_auth, $this->build());
    }
}

?>