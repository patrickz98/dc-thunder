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

    $id = 25;

    $curl = Curl::get($server . "/file/$id?_format=hal_json");
    Simple::write("xxx-$id-file-hal.json", $curl);

    $curl = Curl::get($server . "/file/$id?_format=json");
    Simple::write("xxx-$id-file.json", $curl);

    $curl = Curl::get($server . "/media/$id?_format=hal_json");
    Simple::write("xxx-$id-media-hal.json", $curl);

    $curl = Curl::get($server . "/media/$id?_format=json");
    Simple::write("xxx-$id-media.json", $curl);

    // $patch = [
    //     "bundle" => [
    //         [
    //             "target_id" => "image",
    //             "target_type" => "media_bundle"
    //         ]
    //     ],
    //     "field_image" => [
    //         "url" => "https://upload.wikimedia.org/wikipedia/commons/b/bd/Bending.jpg"
    //     ],
    //     "thumbnail" => [
    //         "url" => "https://upload.wikimedia.org/wikipedia/commons/b/bd/Bending.jpg"
    //     ]
    // ];
    //
    // $curl = Curl::patch($server . "/media/25?_format=json", $patch);
    // Simple::write("xxx-media-patched.json", $curl);
    //
    // echo Simple::prettyJson($curl) . "\n";
    echo "done\n";

    // echo Simple::prettyJson($curl) . "\n";
}

main();

?>
