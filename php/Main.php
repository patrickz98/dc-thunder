<?php

date_default_timezone_set("UTC");

include("./Simple.php");
include("./Curl.php");

include("./Paragraph.php");
include("./Article.php");
include("./DcxImporter.php");


function main()
{
    $server = "http://localhost/thunder";

    // echo Simple::prettyJson(DcxImporter::getDoc()) . "\n";

    $imgSrc = "Einstein.jpg";
    $image = Paragraph::createImage($server, $imgSrc);
    echo Simple::prettyJson($image) . "\n";

    // $paragraphs = [];
    //
    // for ($inx = 0; $inx < 4; $inx++)
    // {
    //     array_push($paragraphs, Paragraph::create($server));
    // }
    //
    // $article = Article::create($server, $paragraphs);
    //
    // echo Simple::prettyJson($article) . "\n";
    // Simple::write("article.json", $article);

    // echo Simple::prettyJson(Curl::get($server . "/seo-title?_format=json")) . "\n";
}

main();

?>
