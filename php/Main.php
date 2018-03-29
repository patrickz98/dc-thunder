<?php

date_default_timezone_set("UTC");

include("./Curl.php");
include("./Simple.php");
include("./Article.php");
include("./DcxImporter.php");
include("./ParagraphFactory.php");

function main()
{
//    $dcxDoc = DcxImporter::getDoc();
    $dcxDoc = DcxImporter::getDoc();
//    echo Simple::prettyJson($dcxDoc) . "\n";

    $headline      = strip_tags($dcxDoc[ "fields" ][ "Headline"       ][ 0 ][ "value" ]);
    $subHeadline   = strip_tags($dcxDoc[ "fields" ][ "SubHeadline"    ][ 0 ][ "value" ]);
    $title         = strip_tags($dcxDoc[ "fields" ][ "Title"          ][ 0 ][ "value" ]);
    $display_title = strip_tags($dcxDoc[ "fields" ][ "_display_title" ][ 0 ][ "value" ]);
//    $htmlBody      = strip_tags($dcxDoc[ "fields" ][ "body"           ][ 0 ][ "value" ]);
    $htmlBody      = $dcxDoc[ "fields" ][ "body"           ][ 0 ][ "value" ];

     echo $htmlBody . "\n";

    $xml = simplexml_load_string("<xml>$htmlBody</xml>");

    foreach($xml->children() as $Item)
    {
        var_dump($Item->getName());
        var_dump($Item);
    }

//    $server     = "http://localhost/thunder";
//    $article    = new Article($server);
//    $paragraphs = new ParagraphFactory($server);
//
//    for ($inx = 0; $inx < 4; $inx++)
//    {
//        $randomText = Simple::getRandomText();
//        $paragraphs->createText($randomText);
//    }
//
//    $paragraphs->createImage("Einstein.jpg");
//
//    $article->addParagraphs($paragraphs->build());
//    $response = $article->post();
//
//    echo Simple::prettyJson($response) . "\n";
    // Simple::write("article.json", $response);

    // echo Simple::prettyJson(Curl::get($server . "/seo-title?_format=json")) . "\n";
}

main();

?>
