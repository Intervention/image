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
                // imagepalettetotruecolor($core);
                $this->gdResourceToTruecolor($core);
                break;

            case IMAGETYPE_JPEG:
                $core = imagecreatefromjpeg($path);
                break;

            case IMAGETYPE_GIF:
                $core = imagecreatefromgif($path);
                // imagepalettetotruecolor($core);
                $this->gdResourceToTruecolor($core);
                break;

            default:
                throw new \Intervention\Image\Exception\NotReadableException(
                    "Unable to read image type ({$this->type}) only use JPG, PNG or GIF images with GD driver."
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

        $image = $this->initFromGdResource($resource);
        $image->mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $binary);

        return $image;
    }

    /**
     * Transform GD resource into Truecolor version
     *
     * @param  resource $resource
     * @return bool
     */
    public function gdResourceToTruecolor(&$resource)
    {
        if (imageistruecolor($resource)) {
            return true;
        }

        $width = imagesx($resource);
        $height = imagesy($resource);

        // new canvas
        $canvas = imagecreatetruecolor($width, $height);

        // fill with transparent color
        imagealphablending($canvas, false);
        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefilledrectangle($canvas, 0, 0, $width, $height, $transparent);
        imagealphablending($canvas, true);

        // copy original
        imagecopy($canvas, $resource, 0, 0, 0, 0, $width, $height);
        imagedestroy($resource);

        $resource = $canvas;

        return true;
    }
}
