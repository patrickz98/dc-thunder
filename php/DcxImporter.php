<?php

class DcxImporter
{
    public static function getDoc()
    {
        $docUrl = "https://dcxtrunk.digicol.de/dcx_trunk/api/document/doc6wkfj97y13ouzj9dhpf";
        $auth = "pz_zierahn:Digicol10";

        return Curl::get($this->docUrl, $auth);
    }
}

?>
