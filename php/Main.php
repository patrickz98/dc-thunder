<?php

date_default_timezone_set('UTC');

include("./Simple.php");
include("./Curl.php");

include("./Paragraph.php");
include("./Article.php");

function main()
{
    $server = "http://localhost/thunder";

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
    // echo Simple::prettyJson(Curl::get($server . "/entity/paragraph/83?_format=json")) . "\n";
    // echo Simple::prettyJson(Curl::get($server . "/media/24?_format=json")) . "\n";
    echo Simple::prettyJson(Curl::get($server . "/entity/file/24?_format=hal_json")) . "\n";
}

main();

?>
