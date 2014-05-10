<?php

namespace Intervention\Image\Gd;

use \Intervention\Image\Image;
use \Intervention\Image\Size;

class Source extends \Intervention\Image\AbstractSource
{
    public function initFromPath($path)
    {
        $info = @getimagesize($path);

        if ($info === false) {
            throw new \Intervention\Image\Exception\NotReadableException(
                "Unable to read image from file ({$path})."
            );
        }

        // define core
        switch ($info[2]) {
            case IMAGETYPE_PNG:
                $core = imagecreatefrompng($path);
                imagepalettetotruecolor($core);
                break;

            case IMAGETYPE_JPEG:
                $core = imagecreatefromjpeg($path);
                break;

            case IMAGETYPE_GIF:
                $core = imagecreatefromgif($path);
                imagepalettetotruecolor($core);
                break;

            default:
                throw new \Intervention\Image\Exception\NotReadableException(
                    "Unable to read image type ({$this->type}) only use JPG, PNG or GIF images."
                );
                break;
        }

        // build image
        $image = $this->initFromGdResource($core);
        $image->mime = $info['mime'];
        $image->setFileInfoFromPath($path);

        return $image;
    }

    public function initFromGdResource($resource)
    {
        return new Image(new Driver, $resource);
    }

    public function initFromImagick($object)
    {
        throw new \Intervention\Image\Exception\NotSupportedException(
            "Gd driver is unable to init from Imagick object."
        );
    }

    public function initFromBinary($binary)
    {
        $resource = @imagecreatefromstring($binary);

        if ($resource === false) {
             throw new \Intervention\Image\Exception\NotReadableException(
                "Unable to init from given binary data."
            );
        }

        $image = $this->initFromGdResource($core);
        $image->mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $binary);

        return $image;
    }
}
