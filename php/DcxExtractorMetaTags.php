<?php

class DcxExtractorMetaTags
{
    private static function extractValues($values)
    {
        $extract = [];

        foreach ($values as $value)
        {
            $metaTag = $value[ "value" ];

            if ($metaTag)
            {
                array_push($extract, $metaTag);
            }
        }

        return $extract;
    }

    public static function getMetaTags($doc)
    {
        $metaKeys = [
            "City",
            "Country",
            "Creator",
            "Keywords",
            "Classification",
//            "Title",
//            "Headline",
//            "Highline",
//            "_display_title",
//            "DateImported",
//            "ImportedBy",
//            "LastEditedBy",
//            "Owner",
//            "Person",
//            "StoryType",
//            "Type",
//            "body",
//            "Organization",
//            "WordCount",
//            "CharCount",
        ];

        $metaTags = [];
        $metaTagsAll = [];

        foreach ($doc[ "fields" ] as $metaKey => $values)
        {
            if (in_array($metaKey, $metaKeys))
            {
                $tags = self::extractValues($values);

                if ($metaKey === "City")
                {
                    $metaTags[ "geo_placename" ] = join(" ", $tags);
                }

                if ($metaKey === "Country")
                {
                    $metaTags[ "geo_region" ] = join(" ", $tags);
                }

                if ($metaKey === "Creator")
                {
                    $metaTags[ "article_author"    ] = join(" ", $tags);
                    $metaTags[ "article_publisher" ] = join(" ", $tags);
                }

                $metaTagsAll += $tags;
            }
        }

        $joinedTags = join(" ", $metaTagsAll);
        $metaTags[ "article_tag"   ] = $joinedTags;
        $metaTags[ "keywords"      ] = $joinedTags;
        $metaTags[ "news_keywords" ] = $joinedTags;

        // Simple::logJson("metaTags", $metaTags);
        // exit(0);

        return $metaTags;
    }
}