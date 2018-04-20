<?php

date_default_timezone_set("UTC");

include("./Curl.php");
include("./Config.php");
include("./Simple.php");
include("./Article.php");
include("./DcxExtractor.php");
include("./ArticleParagraphFactory.php");
include("./ArticlePatch.php");
include("./DcxFeedReader.php");

//function sampleArticle()
//{
//    $paragraphs = new ArticleParagraphFactory();
//
//    for ($inx = 0; $inx < 4; $inx++)
//    {
//        $randomText = Simple::getRandomText();
//        $paragraphs->createText($randomText);
//    }
//
//    $paragraphs->createImage("Einstein.jpg");
//
//    $article = new Article();
//    $article->setTitle(Simple::getRandomText(6));
//    $article->setSeoTitle(Simple::getRandomText(6));
//    $article->addParagraphs($paragraphs->build());
//    $response = $article->post();
//
//    echo Simple::prettyJson($response) . "\n";
//    // Simple::write("article.json", $response);
//}

// #### Metatags hack
function patchMetatags(DcxExtractor $article)
{
    echo "--> Patching metatags... ";

//    $metatagsPatch = [
//        "metatag" => [
//            "value" => $story[ "metatags" ]
//        ]
//    ];

//    $metatagsPatch = [
//        "field_meta_tags" =>  [ "keyword1, keyword2" ]
//    ];

//    $article->patch($metatagsPatch, $nodeId);
//    $article->patch(
//    [
//        "field_seo_title" => [
//            "value" => "Test"
//        ]
//    ], true, $nodeId);

//    $article->patch($metatagsPatch);

//    $metatagsPatch = [
//        "metatag" => [
//            "value" => [
//                "keywords" => "Keyword1 Keyword2 Keyword3"
//            ]
//        ]
//    ];
//
//    $article->patchAsHalJson([ "keywords" => "Keyword1 Keyword2 Keyword3" ]);

    echo "done\n";
}

function export($dcx_doc)
{
    echo "--> Extracting docId=$dcx_doc... ";

    $dcxExtractor = new DcxExtractor(Config::$dcx_server, Config::$dcx_auth);
    $story = $dcxExtractor->getStory($dcx_doc);

    // Simple::logJson("story", $story);
    // exit(0);

    if (! $story)
    {
        return;
    }

    echo "done\n";

    // echo Simple::prettyJson($story) . "\n";
    // exit();

    echo "--> Creating new thunder article... ";
    $article = new Article(Config::$thunder_server, Config::$thunder_auth);
    $article->setTitle(        $story[ "display_title" ]);
    $article->setSeoTitle(     $story[ "sub_headline"   ]);
    $article->setMetaTags(     $story[ "metatags"       ]);
    $article->setTeaserText(   $story[ "teaser_text"    ]);
    $article->createParagraphs($story[ "paragraphs"     ]);

    $nodeId = $article->post();

    echo "done\n";

    echo "--> url=" . Config::$thunder_server . "/node/$nodeId\n";

    // echo "thunder: " . Simple::prettyJson($response) . "\n";
    // echo Simple::prettyJson(Curl::get($server . "/seo-title?_format=json")) . "\n";
}

function exportRssFeed($feed)
{
    $docIds = DcxFeedReader::getDocIds($feed);

    Simple::write("zzz-exported-docs.json", $docIds);

    foreach ($docIds as $dcx_doc)
    {
        export($dcx_doc);
    }
}

function main()
{
    global $argc;
    global $argv;

    if ($argc <= 1)
    {
        export(Config::$dcx_doc);
    }
    else
    {
        export($argv[ 1 ]);
    }
}

main();
//$feedUrl = "https://dcx.digicol.de/dcx/feed?q[profile]=ch6yln2ccrj4hbvapc66j&user=I2xvY2FsX29wZW5sZGFwI3VpZCNwel96aWVyYWhu&key=15a7319f0601a9b8b805c5f5ccc6b6c9";
//exportRssFeed($feedUrl);

?>
