<?php

date_default_timezone_set("UTC");

include("./Curl.php");
include("./Simple.php");
include("./Article.php");
include("./DcxExtractor.php");
include("./ParagraphFactory.php");

function sampleArticle()
{
    $server     = "http://localhost/thunder";
    $article    = new Article($server);
    $paragraphs = new ParagraphFactory($server);

    for ($inx = 0; $inx < 4; $inx++)
    {
        $randomText = Simple::getRandomText();
        $paragraphs->createText($randomText);
    }

    $paragraphs->createImage("Einstein.jpg");

    $article->addParagraphs($paragraphs->build());
    $response = $article->post();

    echo Simple::prettyJson($response) . "\n";
    // Simple::write("article.json", $response);
}

function main()
{
    $dcxExtractor = new DcxExtractor("http://192.168.18.131/dcx/api");
    $story = $dcxExtractor->getStory("doc6wyp0ms0sg51mksj7omy");

    echo "dcx: " . Simple::prettyJson($story) . "\n";

    $server     = "http://localhost/thunder";
    $article    = new Article($server);
    $paragraphs = new ParagraphFactory($server);

    foreach ($story[ "paragraphs" ] as $inx => $paragraph)
    {
        $type = $paragraph[ "type" ];

        if ($type === "text")
        {
            $paragraphs->createText($paragraph[ "text" ]);
        }

        if ($type === "image")
        {
            $paragraphs->createImage($paragraph[ "src" ]);
        }
    }


    $article->addParagraphs($paragraphs->build());
    $response = $article->post();

    echo "thunder: " . Simple::prettyJson($response) . "\n";

    // echo Simple::prettyJson(Curl::get($server . "/seo-title?_format=json")) . "\n";
}

main();

?>
