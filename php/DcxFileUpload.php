<?php

class DcxFileUpload
{
    private static function createDcxDoc($file)
    {
        // STUB!
    }

    private static function getFileId($dcxResponse)
    {
        $location = $dcxResponse[ "location" ];
        preg_match("/file\/(file.*?)$/", $location, $matches);

        return $matches[ 1 ];
    }

    public static function upload($file)
    {
        Simple::logJson("file", $file);

        $url  = Config::$dcx_server . "/_file_upload";
        $data = file_get_contents($file[ "src" ]);

        $headers = [
            "Slug: " . $file[ "filename" ],
            "Content-Type: " . $file[ "filemime" ],
            "Accept: application/json"
        ];

        $dcxResponse = Curl::postRaw($url, Config::$dcx_auth, $data, $headers);

        Simple::logJson("dcx-response", $dcxResponse);

        $file[ "fileId" ] = self::getFileId($dcxResponse);

        return self::getFileId($dcxResponse);
    }
}

?>