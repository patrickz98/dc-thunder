<?php

class Curl
{
    public static function post($url, $data)
    {
        $headers = [
            "Content-Type: application/json",
            "Accept: application/json"
        ];

        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPHEADER,     $headers);
        curl_setopt($process, CURLOPT_USERPWD,        "patrick:1234");
        curl_setopt($process, CURLOPT_CUSTOMREQUEST,  "POST");
        curl_setopt($process, CURLOPT_POSTFIELDS,     Simple::prettyJson($data));
        curl_setopt($process, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($process);
        curl_close($process);

        return Simple::parseJson($result);
    }
}

?>
