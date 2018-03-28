<?php

date_default_timezone_set('UTC');

include("./Simple.php");
include("./Curl.php");

include("./Paragraph.php");
include("./Article.php");

function main()
{
    $server = "http://localhost/thunder";

    $paragraphs = [];

    for ($inx = 0; $inx < 4; $inx++)
    {
        array_push($paragraphs, Paragraph::create($server));
    }

    $article = Article::create($server, $paragraphs);

    echo Simple::prettyJson($article) . "\n";

    Simple::write("article.json", $article);

    // echo Simple::prettyJson(Curl::post()) . "\n";
    // echo Curl::post() . "\n";

    // $time = Simple::getTimeIso();
    // echo "--> Time: $time\n";
    //
    // $article = [
    //     "title" => "Title $time",
    //     "title_seo" => "SEO Title $time"
    // ];
    //
    // echo Simple::prettyJson($article) . "\n";
}

main();

?>
