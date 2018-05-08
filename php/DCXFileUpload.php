<?php

class DCXFileUpload
{
    private static function getFileId($dcxResponse)
    {
        $location = $dcxResponse[ "location" ];
        preg_match("/file\/(file.*?)$/", $location, $matches);

        return $matches[ 1 ];
    }

    /*
    curl -H 'Content-Type: image/jpeg' -H 'Slug: dcx-logo.jpg' --data-binary @/opt/dcx/static_www/images/dcx-logo.jpg -u 'user:secret' 'http://example.com/dcx/api/_file_upload'
    {
        "@context": "\/dcx\/api\/_context",
        "_type": "dcx:success",
        "dcx_code": 1,
        "location": "\/dcx\/api\/file\/file6mvmlo9idd1jyszg1bz",
        "status": 201
    }
    */
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

        return self::getFileId($dcxResponse);
    }
}

?>