<?php

class DcxStoryBody
{
    private static function parse2Html($paragraph)
    {
        $type = $paragraph[ "type" ];

        if ($type === "text")
        {
            return $paragraph[ "text" ];
        }

        return "";
    }

    public static function build($paragraphs)
    {
        $htmlBody = "";

        foreach ($paragraphs as $paragraph)
        {
            $htmlBody .= self::parse2Html($paragraph);
        }

        return [
            [
                "_type" => "xhtml",
                "lang"  => "",
                "value" => $htmlBody
            ]
        ];
    }
}

?>