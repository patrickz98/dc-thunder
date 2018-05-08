<?php

date_default_timezone_set("UTC");

const CREATED_THUNDER_URLS_FILE = "zzz-created-thunder-urls.json";

include("./Curl.php");
include("./Config.php");
include("./Simple.php");
include("./Article.php");
include("./DcxExport.php");
include("./ArticlePatch.php");
include("./ThunderExport.php");
include("./DcxFeedReader.php");
include("./ArticleParagraphFactory.php");

function patch($nodeId)
{
    echo "--> Patching... ";

    $patch = [
        "title" => [
            [
                "value" => "Example node title UPDATED!"
            ]
        ]
    ];

    $patcher = new ArticlePatch(Config::$thunder_server, Config::$thunder_auth);
    $patcher->patch($nodeId, $patch);

    // $article->patchAsHalJson([ "keywords" => "Keyword1 Keyword2 Keyword3" ]);

    echo "done\n";
}

function export($dcx_doc)
{
    echo "--> Extracting docId=$dcx_doc...       | ";

    $dcxExtractor = new DcxExport(Config::$dcx_server, Config::$dcx_auth);
    $story = $dcxExtractor->getStory($dcx_doc);

    if (! $story)
    {
        return null;
    }

    echo "done\n";

    // Simple::write("zzz-story.json", $story);
    // Simple::logJson("story", $story);
    // exit(0);

    echo "--> Creating new thunder article...    | ";

    $article = new Article(Config::$thunder_server, Config::$thunder_auth);
    $article->setUuid(         $story[ "uuid"           ]);
    $article->setTitle(        $story[ "display_title"  ]);
    $article->setSeoTitle(     $story[ "display_title"  ]);
    $article->setMetaTags(     $story[ "metatags"       ]);
    $article->setTeaserText(   $story[ "teaser_text"    ]);
    $article->createParagraphs($story[ "paragraphs"     ]);

    $nodeId = $article->post();

    if ($nodeId)
    {
        echo "done\n";

        $thunderUrl = Config::$thunder_server . "/node/" . $nodeId;
        echo "--> url=$thunderUrl\n";

        return $thunderUrl;
    }
    else
    {
        return null;
    }
}

function exportRssFeed($feed)
{
    $docIds = DcxFeedReader::getDocIds($feed);

    if ($docIds)
    {
        $thunderUrls = [];

        foreach ($docIds as $dcx_doc)
        {
            $url = export($dcx_doc);
            if ($url) array_push($thunderUrls, $url);
        }

        Simple::write(CREATED_THUNDER_URLS_FILE, $thunderUrls);
    }
}

function deleteExportedRssFeed()
{
    $thunderUrls = Simple::read(CREATED_THUNDER_URLS_FILE);

    if ($thunderUrls)
    {
        foreach ($thunderUrls as $thunderUrl)
        {
            echo "Delete: $thunderUrl\n";
            Curl::delete($thunderUrl, Config::$thunder_auth);
        }
    }

    unlink(CREATED_THUNDER_URLS_FILE);
}

function main()
{
    global $argc;
    global $argv;

    if ($argc <= 1)
    {
        export(Config::$dcx_demo_doc);
        return;
    }

    if ($argv[ 1 ] === "feed")
    {
        // $feedUrl = "https://dcx.digicol.de/dcx/feed?q[profile]=ch6yln2ccrj4hbvapc66j&user=I2xvY2FsX29wZW5sZGFwI3VpZCNwel96aWVyYWhu&key=15a7319f0601a9b8b805c5f5ccc6b6c9";
        // $feedUrl = "https://dcx.digicol.de/dcx/feed?q[channel][]=channel_pool_story&user=I2xvY2FsX29wZW5sZGFwI3VpZCNwel96aWVyYWhu&key=ee241233a76751fea433d4745da6e4e1";
        // $feedUrl = "https://dcx.digicol.de/dcx/feed?q[channel][]=ch030dcxsystempoolatext&user=I2xvY2FsX29wZW5sZGFwI3VpZCNwel96aWVyYWhu&key=4c6005d85b120e01661539d3f6d71600";
        // $feedUrl = "https://dcx.digicol.de/dcx/feed?q[channel][]=ch035dcxsystempoolpages&user=I2xvY2FsX29wZW5sZGFwI3VpZCNwel96aWVyYWhu&key=9dcc82c3974be6538ddf2524459879a6";
        // $feedUrl = "https://dcx.digicol.de/dcx/feed?q[channel][]=ch6c24a8b6wo0osj923jb&user=I2xvY2FsX29wZW5sZGFwI3VpZCNwel96aWVyYWhu&key=48e8e365ee6c83145297ebe7089a0fa3";
        $feedUrl = "https://dcx.digicol.de/dcx/feed?q[channel][]=ch695rfutxf5494jurmb0&user=I2xvY2FsX29wZW5sZGFwI3VpZCNwel96aWVyYWhu&key=ad3ed54fb9bb9b020f3b6b753728382e";
        exportRssFeed($feedUrl);
    }
    else if ($argv[ 1 ] === "delete")
    {
        deleteExportedRssFeed();
    }
    else
    {
        export($argv[ 1 ]);
    }
}

Simple::logJson("", ThunderExport::exportArticle());
//main();

//patchMetatags(287);

?>
