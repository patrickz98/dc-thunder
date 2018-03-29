<?php

class Curl
{
    private static function curl_init($url, $auth)
    {
        $headers = [
            "Content-Type: application/json",
            "Accept: application/json"
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER,     $headers);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST,  "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if ($auth)
        {
            curl_setopt($curl, CURLOPT_USERPWD, $auth);
        }

        return $curl;
    }

    public static function get($url, $auth = "patrick:1234")
    {
        $curl = Curl::curl_init($url, $auth);

        $result = curl_exec($curl);
        curl_close($curl);

        return Simple::parseJson($result);
    }

    public static function post($url, $data, $auth = "patrick:1234")
    {
        $curl = Curl::curl_init($url, $auth);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS,    Simple::prettyJson($data));

        $result = curl_exec($curl);
        curl_close($curl);

        return Simple::parseJson($result);
    }

    public static function postHalJson($url, $data, $auth = "patrick:1234")
    {
        $curl = Curl::curl_init($url, $auth);

        $headers = [
            "Content-Type: application/hal+json",
            "Accept: application/hal+json"
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER,     $headers);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS,    Simple::prettyJson($data));

        $result = curl_exec($curl);
        curl_close($curl);

        return Simple::parseJson($result);
    }

    public static function patch($url, $data, $auth = "patrick:1234")
    {
        $curl = Curl::curl_init($url, $auth);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS,    Simple::prettyJson($data));

        $result = curl_exec($curl);
        curl_close($curl);

        return Simple::parseJson($result);
    }
}

?>
