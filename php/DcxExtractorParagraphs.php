<?php

include("./DcxExtractorImages.php");

class DcxExtractorParagraphs
{
    private $doc;
    private $imageIds;
    private $galleries;

    function __construct($doc)
    {
        $this->doc       = $doc;
        $this->imageIds  = DcxExtractorImages::getImageIds($doc);
        $this->galleries = DcxExtractorImages::getGalleries($doc);
    }

    public function parseHtml($htmlBody)
    {
        $json = [];
        $xml = simplexml_load_string("<xml>$htmlBody</xml>");

        foreach($xml->children() as $dcxParagraph)
        {
            array_push($json, Simple::xmlToJson($dcxParagraph));
        }

        return $json;
    }

    public function getSlotValue($attributes)
    {
        $config = Simple::parseJson($attributes[ "data-dcx_media_config" ]);
        $params = $config[ "content" ][ "params" ];

        parse_str($params, $configQuery);

        return $configQuery[ "dcx_mg_attributes_slot_value" ];
    }

    private function transformParagraph($htmlParagraph)
    {
        $attributes = $htmlParagraph[ "@attributes" ];

        if ((! $attributes) && $htmlParagraph[ "0" ])
        {
            return [
                "type" => "text",
                "text" => trim($htmlParagraph[ "0" ])
            ];
        }

        if (strpos($attributes[ "class" ], "text"))
        {
            return [
                "type" => "text",
                "text" => trim($htmlParagraph[ "0" ])
            ];
        }

        $span = $htmlParagraph[ "span" ];
        if ($span)
        {
            $text = "";

            if (is_array($span))
            {
                foreach ($span as $txt)
                {
                    $text .= $txt;
                }
            }

            if (is_string($span))
            {
                $text = $span;
            }

            return [
                "type" => "text",
                "text" => $text
            ];
        }

        if (array_key_exists("br", $htmlParagraph))
        {
            return null;
        }

        if ($htmlParagraph[ "a" ])
        {
            return [
                "type" => "text",
                "text" => "<a href=\"" . $htmlParagraph[ "a" ] . "\">" .  $htmlParagraph[ "a" ] . "</a>"
            ];
        }

        if ($attributes[ "href" ])
        {
            // echo "href=" . Simple::prettyJson($dcxParagraph) . "\n";

            return [
                "type" => "text",
                "text" => "<a href=\"" . $attributes[ "href" ] . "\">" .  $attributes[ "href" ] . "</a>"
            ];
        }

        if ($attributes[ "class" ] === "title")
        {
            // #### STUB!
            return null;
        }

        $type = $attributes[ "data-dcx_media_type" ];

        if ($type === "imagegroups")
        {
            return [
                "type" => "image",
                "src"  => $this->imageIds[ $this->getSlotValue($attributes) ]
            ];
        }

        if ($type === "twitter")
        {
            return [
                "type" => "tweet",
                "src"  => $this->getSlotValue($attributes)
            ];
        }

        if ($type === "youtube")
        {
            return [
                "type" => "youtube",
                "src"  => $this->getSlotValue($attributes)
            ];
        }

        if ($type === "gallery")
        {
            return [
                "type"   => "gallery",
                "images" => $this->galleries[ $this->getSlotValue($attributes) ]
            ];
        }

        if (count($htmlParagraph) > 0)
        {
            echo "--> unknown paragraph: " . Simple::prettyJson($htmlParagraph) . "\n";
        }

        return null;
    }

    public function getBodyPartsHtml($html)
    {
        $paragraphs = [];
        $htmlParagraphs = $this->parseHtml($html);

        foreach ($htmlParagraphs as $htmlParagraph)
        {
            $paragraph = $this->transformParagraph($htmlParagraph);

            if ($paragraph)
            {
                array_push($paragraphs, $paragraph);
            }
        }

        return $paragraphs;
    }

    public function getBodyPartsPlain($str)
    {
        $text = "";

        foreach (explode("\n", $str) as $pTag)
        {
            $text .= "<p>$pTag</p>";
        }

        return [
            [
                "type" => "text",
                "text" => $text
            ]
        ];
    }

    public function getBodyParagraphs($doc)
    {
        $paragraphs = [];

        $body = $doc[ "fields" ][ "body" ];

        foreach ($body as $bodyPart)
        {
            $strContent = $bodyPart[ "value" ];
            $parts = [];

            // Process as HTML
            if ($strContent[ 0 ] === "<")
            {
                $parts = $this->getBodyPartsHtml($strContent);
            }
            else
            {
                $parts = $this->getBodyPartsPlain($strContent);
            }

            $paragraphs = array_merge($paragraphs, $parts);
        }

        return $paragraphs;
    }

    public function getDefaultParagraphs($doc)
    {
        $paragraphs = [];

        $uri = $doc[ "fields" ][ "URI" ][ 0 ][ "value" ];
        if ($uri && Simple::endsWith($uri, ".jpg"))
        {
            array_push($paragraphs, [
                "type" => "image",
                "src" => $uri
            ]);
        }

        $transcript = $doc[ "fields" ][ "Transcript" ];
        if ($transcript)
        {
            array_push($paragraphs, [
                "type" => "text",
                "text" => trim($transcript[ 0 ][ "value" ])
            ]);
        }

        return $paragraphs;
    }

    public function getParagraphs()
    {
        $paragraphs = [];
        $paragraphs = array_merge($paragraphs, $this->getDefaultParagraphs($this->doc));
        $paragraphs = array_merge($paragraphs, $this->getBodyParagraphs($this->doc));

        return $paragraphs;
    }
}

?>