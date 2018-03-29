<?php

date_default_timezone_set("UTC");

include("./Simple.php");
include("./Curl.php");

include("./Paragraphs.php");
include("./Article.php");
include("./DcxImporter.php");


function main()
{
    $server = "http://localhost/thunder";

    // echo Simple::prettyJson(DcxImporter::getDoc()) . "\n";

    $paragraphs = new Paragraphs($server);

    for ($inx = 0; $inx < 4; $inx++)
    {
        $randomText = Simple::getRandomText();
        $paragraphs->createText($randomText);
    }

    $paragraphs->createImage("Einstein.jpg");

    $article = Article::create($server, $paragraphs->build());

    echo Simple::prettyJson($article) . "\n";
    Simple::write("article.json", $article);

    // echo Simple::prettyJson(Curl::get($server . "/seo-title?_format=json")) . "\n";
}

main();

?>
