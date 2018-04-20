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

    public function patch($patch, $nodeId, $override)
    {
        $url = $this->server . "/node/$nodeId?_format=json";

        $pre = Curl::get($url, Config::$dcx_auth);
        //unset($pre[ "metatag" ][ "value" ][ "og_updated_time" ]);
        //unset($pre[ "metatag" ][ "value" ][ "keywords" ]);
        //unset($pre[ "metatag" ]);
        //unset($pre[ "field_meta_tags" ]);

        if ($override)
        {
            $post = array_merge($pre, $patch);
        }
        else
        {
            $post = array_merge_recursive($pre, $patch);
        }

        // unset($post[ "field_seo_title" ]);

        $response = Curl::patch($url, $this->auth, $post);

        Simple::write("zzz-patch-pre.json",      $pre);
        Simple::write("zzz-patch-post.json",     $post);
        Simple::write("zzz-patch-response.json", $response);
    }

    /*
    public function patchAsHalJson($patch, $nodeId)
    {
        $url = $this->server . "/node/$nodeId?_format=hal_json";

        $pre = Curl::get($url, Config::$dcx_auth);
        unset($pre[ "_links" ]);
        unset($pre[ "_embedded" ]);
        //unset($pre[ "metatag" ][ "value" ][ "keywords" ]);
        //unset($pre[ "field_meta_tags" ]);

        $post = $pre;
        $post[ "metatag" ][ 0 ][ "value" ] += $patch;
        $response = Curl::patch($url, $this->auth, $post);

        Simple::write("zzz-hal-patch-pre.json",      $pre);
        Simple::write("zzz-hal-patch-post.json",     $post);
        Simple::write("zzz-hal-patch-response.json", $response);
    }
    */
}