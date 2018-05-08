<?php

class DcxStoryBodyImage
{
    private $server;
    private $auth;

    private $images;

    function __construct($dcx_server, $dcx_auth)
    {
        $this->server = $dcx_server;
        $this->auth   = $dcx_auth;
    }

    public function setSource($paragraphs)
    {
        $this->images = [];

        // filter images
        foreach ($paragraphs as $paragraph)
        {
            if ($paragraph[ "type" ] === "image")
            {
                array_push($this->images, $paragraph);
            }
        }
    }

    public function build()
    {
        return null;
    }
}

?>