<?php

class DcxStoryBodyImage
{
    private $server;
    private $auth;

    private $images;
    private $fileIds;

    function __construct($dcx_server, $dcx_auth)
    {
        $this->server = $dcx_server;
        $this->auth   = $dcx_auth;
    }

    public function setSource($paragraphs)
    {
        $this->images  = [];
        $this->fileIds = [];

        // filter images
        foreach ($paragraphs as $paragraph)
        {
            if ($paragraph[ "type" ] === "image")
            {
                array_push($this->images, $paragraph);
            }
        }

    }

    private function uploadFiles()
    {
        //$url = Config::$dcx_server . "/_file_upload";
        $url = Config::$dcx_server . "/_upload/native_hotfolder";

        foreach ($this->images as $imgInfo)
        {
            //print_r($imgInfo);

            $header = [
                "Slug"         => $imgInfo[ "data" ][ "filename" ],
                "Content-Type" => $imgInfo[ "data" ][ "filemime" ]
            ];

            $binaryData = file_get_contents($imgInfo[ "data" ][ "src" ]);
            $postfields = array(
                "file[input]" => "@$binaryData",
                "job_metadata[form_data][addtags][Title][][value]" => "Hasdhfaklsdhfkjasdkj");

            $response = Curl::postRaw($url, Config::$dcx_auth, $data, $header);
            $fileSrc = $response[ "location" ];

            Simple::logJson("response", $response);

            if ($fileSrc)
            {
                // /dcx/api/file/
                $fileId = substr($fileSrc, 14);
                array_push($this->fileIds, $fileId);
            }

            //print_r($response);
            //exit();
        }

        //print_r($this->fileIds);

        //Curl::postRaw($url, Config::$dcx_auth, );
    }

    public function build()
    {
        $this->uploadFiles();
        return null;
    }
}

?>