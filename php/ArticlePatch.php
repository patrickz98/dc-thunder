<?php

class ArticlePatch
{
    private $server;
    private $auth;

    function __construct($thunder_server, $thunder_auth)
    {
        $this->server = $thunder_server;
        $this->auth   = $thunder_auth;
    }

    public function patch($metatags)
    {
        // $url = $this->server . "/node/$nodeId?_format=json";
        $url = $this->server . "/default-seo-title-2018-04-13t1153570000?_format=json";

        $old = Curl::get($url, Config::$dcx_auth);
        Simple::write("zzz-article-old.json", $old);
        $old[ "metatag" ][ "value" ] += $metatags;

        $response = Curl::patch($url, $this->auth, $old);

        Simple::write("zzz-article-old-patch.json", $old);
        Simple::write("zzz-article-patch-response.json", $response);
    }
}