<?php

class DcxExtractorImages
{
    public static function getImages($server, $auth, $doc)
    {
        $parsedImages = [];
        $images = $doc[ "fields" ][ "Image" ];

        if ($images)
        {
            foreach ($images as $inx => $value)
            {
                $targetImgId = $value[ "taggroup_id" ];
                $imageDocId = $value[ "fields" ][ "DocumentRef" ][ 0 ][ "_id" ];

                // remove dcxapi:document/
                $imageDocId = substr($imageDocId, 16);
                $imageDoc   = DcxExtractor::getDoc($server, $auth, $imageDocId);
                $fileId     = $imageDoc[ "files" ][ 0 ][ "_id" ];

                // dcxapi:file/
                $fileId = substr($fileId, 12);

                $file = DcxExtractor::getFile($server, $auth, $fileId);

                $parsedImages[ $targetImgId ] = $file[ "properties" ][ "_file_url_absolute" ];
            }
        }

        return $parsedImages;
    }
}

?>