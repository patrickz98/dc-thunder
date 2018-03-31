<?php

date_default_timezone_set("UTC");

include("./Curl.php");
include("./Config.php");
include("./Simple.php");
include("./Article.php");
include("./DcxExtractor.php");
include("./ParagraphFactory.php");

function sampleArticle()
{
    $paragraphs = new ParagraphFactory();

    for ($inx = 0; $inx < 4; $inx++)
    {
        $randomText = Simple::getRandomText();
        $paragraphs->createText($randomText);
    }

    $paragraphs->createImage("Einstein.jpg");

    $article = new Article();
    $article->setTitle(Simple::getRandomText(6));
    $article->setSeoTitle(Simple::getRandomText(6));
    $article->addParagraphs($paragraphs->build());
    $response = $article->post();

    echo Simple::prettyJson($response) . "\n";
    // Simple::write("article.json", $response);
}

function main()
{
    // dcx.digicol
    // $story = DcxExtractor::getStory("doc6zhtpoemzpw8gb7gfms");
    // VM
    $story = DcxExtractor::getStory("doc6wyp0ms0sg51mksj7omy");

    echo "dcx: " . Simple::prettyJson($story) . "\n";

    $paragraphs = new ParagraphFactory();

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

    $article = new Article();
    $article->setTitle($story[ "headline" ]);
    $article->setSeoTitle($story[ "subHeadline" ]);
    $article->addParagraphs($paragraphs->build());

    $response = $article->post();

    echo "thunder: " . Simple::prettyJson($response) . "\n";

    // echo Simple::prettyJson(Curl::get($server . "/seo-title?_format=json")) . "\n";
}

main();

?>
