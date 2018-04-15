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

    public function patch($patch, $nodeId)
    {
        $url = $this->server . "/node/$nodeId?_format=json";

        $pre = Curl::get($url, Config::$dcx_auth);
        //unset($pre[ "metatag" ][ "value" ][ "og_updated_time" ]);
        //unset($pre[ "metatag" ][ "value" ][ "keywords" ]);
        //unset($pre[ "field_meta_tags" ]);

        $post = array_merge_recursive($pre, $patch);
        //$post = array_merge($pre, $patch);
        $response = Curl::patch($url, $this->auth, $post);

        Simple::write("zzz-patch-pre.json",      $pre);
        Simple::write("zzz-patch-post.json",     $post);
        Simple::write("zzz-patch-response.json", $response);
    }
}