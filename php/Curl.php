<?php

class Curl
{
    private static function curl_init($url)
    {
        $headers = [
            "Content-Type: application/json",
            "Accept: application/json"
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER,     $headers);
        curl_setopt($curl, CURLOPT_USERPWD,        "patrick:1234");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST,  "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return $curl;
    }

    public static function get($url)
    {
        $curl = Curl::curl_init($url);

        $result = curl_exec($curl);
        curl_close($curl);

        return Simple::parseJson($result);
    }

    public static function post($url, $data)
    {
        $curl = Curl::curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS,    Simple::prettyJson($data));

        $result = curl_exec($curl);
        curl_close($curl);

        return Simple::parseJson($result);
    }

    public static function patch($url, $data)
    {
        $curl = Curl::curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS,    Simple::prettyJson($data));

        $result = curl_exec($curl);
        curl_close($curl);

        return Simple::parseJson($result);
    }
}

?>
