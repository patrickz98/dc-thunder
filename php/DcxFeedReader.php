<?php

class DcxFeedReader
{
    private static function getDocId($feedEntry)
    {
        // "id": "http://dcx.digicol.de/dcx/ui/document/doc6zsie3px1r41706g1aby",
        $idUrl = $feedEntry[ "id" ];
        preg_match("/document\/(.*?)$/", $idUrl, $id);

        return $id[ 1 ];
    }

    public static function getDocIds($feedUrl)
    {
        $feedXml = Curl::getRaw($feedUrl, Config::$dcx_auth);

        if ($feedXml[ 0 ] !== "<")
        {
            echo "Error: feedUrl=$feedUrl feedXml=$feedXml\n";
            return null;
        }

        $feed = simplexml_load_string($feedXml);
        $feed = Simple::xmlToJson($feed);

        // Simple::logJson("rss", $feed);

        $docIds = [];
        foreach ($feed[ "entry" ] as $feedEntry)
        {
            array_push($docIds, self::getDocId($feedEntry));
        }

        return $docIds;
    }
}

?>