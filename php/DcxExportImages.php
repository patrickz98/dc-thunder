<?php

class DcxExportImages
{
    private static function getImageTargetIds($images)
    {
        $parsedImages = [];

        if ($images)
        {
            foreach ($images as $value)
            {
                $targetImgId = $value[ "taggroup_id" ];
                $imageDocId  = $value[ "fields" ][ "DocumentRef" ][ 0 ][ "_id" ];

                // remove dcxapi:document/
                $imageDocId = substr($imageDocId, 16);
                $imageDoc   = DcxExport::getDoc($imageDocId);
                $fileId     = $imageDoc[ "files" ][ 0 ][ "_id" ];

                // remove dcxapi:file/
                $fileId = substr($fileId, 12);

                $file = DcxExport::getFile($fileId);

                $parsedImages[ $targetImgId ] = $file[ "properties" ][ "_file_url_absolute" ];
            }
        }

        return $parsedImages;
    }

    public static function getImageIds($doc)
    {
        return self::getImageTargetIds($doc[ "fields" ][ "Image" ]);
    }

    public static function getGalleries($doc)
    {
        $galleries = [];
        $dcxGallery = $doc[ "fields" ][ "Gallery" ];
        $targetImages = self::getImageTargetIds($doc[ "fields" ][ "GalleryImage" ]);

        if ($dcxGallery)
        {
            foreach ($dcxGallery as $inx => $gallery)
            {
                $targetId = $gallery[ "taggroup_id" ];
                $galleries[ $targetId ] = [];

                foreach ($gallery[ "fields" ][ "TagGroupRef" ] as $img)
                {
                    $target = $img[ "ref_taggroup_id" ];
                    $imgSrc = $targetImages[ $target ];
                    array_push($galleries[ $targetId ], $imgSrc);
                }
            }
        }

        return $galleries;
    }
}

?>