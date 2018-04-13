<?php

date_default_timezone_set("UTC");

include("./Curl.php");
include("./Config.php");
include("./Simple.php");
include("./Article.php");
include("./DcxExtractor.php");
include("./ArticleParagraphFactory.php");
include("./PatchArticle.php");

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

    // #### Metatags hack
    // $patcher = new PatchArticle(Config::$thunder_server, Config::$thunder_auth);
    // $patcher->patch($story[ "metatags" ]);
    // exit(0);

    // echo Simple::prettyJson($story) . "\n";
    // exit();

    echo "--> Creating new thunder article... ";
    $article = new Article(Config::$thunder_server, Config::$thunder_auth);
    $article->setTitle(        $story[ "headline"     ]);
    $article->setSeoTitle(     $story[ "sub_headline" ]);
    $article->setMetaTags(     $story[ "metatags"     ]);
    $article->setTeaserText(   $story[ "teaser_text"  ]);
    $article->createParagraphs($story[ "paragraphs"   ]);

    $response = $article->post();

    Simple::write("zzz.json", $response);

    echo "done\n";

    echo "--> url=" . Config::$thunder_server . "/node/" . $response[ "nid" ][ 0 ][ "value" ] . "\n";

    // echo "thunder: " . Simple::prettyJson($response) . "\n";
    // echo Simple::prettyJson(Curl::get($server . "/seo-title?_format=json")) . "\n";
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

?>
