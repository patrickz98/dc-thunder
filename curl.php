<?php

    function getJson($url, $file = null)
    {
        $rawJson = file_get_contents($url);
        $parsedJson = json_decode($rawJson, true);
        $prettyJson = json_encode($parsedJson, JSON_PRETTY_PRINT);

        if ($file == null)
        {
            echo $prettyJson . "\n";
            return $parsedJson;
        }

        $myfile = @fopen($file, "w");
        @fwrite($myfile, $prettyJson);
        @fclose($myfile);

        return $parsedJson;
    }

    function extractImage(&$exportJson, $paragraph)
    {
        $id = $paragraph[ "id" ];

        $url = "http://h2758593.stratoserver.net/thunder/jsonapi/paragraph/image/$id/field_image";
        $imgInfo = getJson($url, "tmp/article-field_image-$id.json");

        $thumbnailUrl = $imgInfo[ "data" ][ "relationships" ][ "thumbnail" ][ "links" ][ "related" ];
        $thumbnailInfo = getJson($thumbnailUrl, "tmp/article-thumbnail-$id.json");

        $imgSrc = $thumbnailInfo[ "data" ][ "attributes" ][ "url" ];

        $imgInfo = [
            "type" => "paragraph--image",
            "src" => $imgSrc
        ];

        array_push($exportJson[ "paragraphs" ], $imgInfo);
    }

    function extractText(&$exportJson, $paragraph)
    {
        $txtInfo = [
            "type" => "paragraph--text",
            "body" => $paragraph[ "attributes" ][ "field_text" ][ "value" ]
        ];

        array_push($exportJson[ "paragraphs" ], $txtInfo);
    }

    function addMetaData(&$exportJson, $article)
    {
        $attributes = $article[ "data" ][ "attributes" ];

        $exportJson[ "uuid" ]        = $attributes[ "uuid" ];
        $exportJson[ "title" ]       = $attributes[ "title" ];
        $exportJson[ "meta_tags" ]   = $attributes[ "field_meta_tags" ];
        $exportJson[ "seo_title" ]   = $attributes[ "field_seo_title" ];
        $exportJson[ "teaser_text" ] = $attributes[ "field_teaser_text" ];
    }

    function main()
    {
        @mkdir("tmp");

        // http://h2758593.stratoserver.net/thunder/jsonapi/node/article/67e62876-d58a-4aba-892b-a55c2f365533?_format=json
        $uuid = "67e62876-d58a-4aba-892b-a55c2f365533";
        $article = getJson("http://h2758593.stratoserver.net/thunder/jsonapi/node/article/$uuid", "tmp/article.json");

        $exportJson = array();
        addMetaData($exportJson, $article);

        $exportJson[ "paragraphs" ] = array();

        /*
            getJson("http://h2758593.stratoserver.net/thunder/jsonapi/node/article/$uuid/type",               "tmp/article-type.json");
        	getJson("http://h2758593.stratoserver.net/thunder/jsonapi/node/article/$uuid/uid",                "tmp/article-uid.json");
        	getJson("http://h2758593.stratoserver.net/thunder/jsonapi/node/article/$uuid/revision_uid",       "tmp/article-revision_uid.json");
        	getJson("http://h2758593.stratoserver.net/thunder/jsonapi/node/article/$uuid/menu_link",          "tmp/article-menu_link.json");
        	getJson("http://h2758593.stratoserver.net/thunder/jsonapi/node/article/$uuid/field_tags",         "tmp/article-field_tags.json");
        	getJson("http://h2758593.stratoserver.net/thunder/jsonapi/node/article/$uuid/field_teaser_media", "tmp/article-field_teaser_media.json");
        */

        $field_paragraphs = getJson("http://h2758593.stratoserver.net/thunder/jsonapi/node/article/$uuid/field_paragraphs", "tmp/article-field_paragraphs.json");
        $data = $field_paragraphs[ "data" ];

        foreach ($data as $inx => $paragraph)
        {
            $type = $paragraph[ "type" ];
            echo "--> type=$type\n";

            if ($type === "paragraph--image")
            {
                extractImage($exportJson, $paragraph);
            }

            if ($type === "paragraph--text")
            {
                extractText($exportJson, $paragraph);
            }
        }

        echo json_encode($exportJson, JSON_PRETTY_PRINT) . "\n";

        getJson("http://10.20.0.138:12345/solr/core-tex6whfjz37dlvsjvqvd0u-index1/select?q=_id:doc6wyp0ms0sg51mksj7omy&indent=true&wt=json", "tmp/solr.json");
    }

    main();
?>
