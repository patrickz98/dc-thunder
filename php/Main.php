<?php

date_default_timezone_set("UTC");

include("./Simple.php");
include("./Curl.php");

include("./Article.php");
include("./DcxImporter.php");

function main()
{
    $server = "http://localhost/thunder";

    // echo Simple::prettyJson(DcxImporter::getDoc()) . "\n";

    $article = new Article($server);

    for ($inx = 0; $inx < 4; $inx++)
    {
        $randomText = Simple::getRandomText();
        $article->createText($randomText);
    }

    $article->createImage("Einstein.jpg");

    $response = $article->create();

    echo Simple::prettyJson($response) . "\n";
    // Simple::write("article.json", $response);

    // echo Simple::prettyJson(Curl::get($server . "/seo-title?_format=json")) . "\n";
}

main();

?>
