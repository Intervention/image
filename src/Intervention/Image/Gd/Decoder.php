<?php

namespace Intervention\Image\Gd;

use \Intervention\Image\Image;
use \Intervention\Image\Size;

class Decoder extends \Intervention\Image\AbstractDecoder
{
    /**
     * Initiates new image from path in filesystem
     *
     * @param  string $path
     * @return \Intervention\Image\Image
     */
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
                $this->gdResourceToTruecolor($core);
                break;

            case IMAGETYPE_JPEG:
                $core = imagecreatefromjpeg($path);
                break;

            case IMAGETYPE_GIF:
                $core = imagecreatefromgif($path);
                $this->gdResourceToTruecolor($core);
                break;

            default:
                throw new \Intervention\Image\Exception\NotReadableException(
                    "Unable to read image type. GD driver is only able to decode JPG, PNG or GIF files."
                );
        }

        // build image
        $image = $this->initFromGdResource($core);
        $image->mime = $info['mime'];
        $image->setFileInfoFromPath($path);

        return $image;
    }

    /**
     * Initiates new image from GD resource
     *
     * @param  Resource $resource
     * @return \Intervention\Image\Image
     */
    public function initFromGdResource($resource)
    {
        return new Image(new Driver, $resource);
    }

    /**
     * Initiates new image from Imagick object
     *
     * @param  Imagick $object
     * @return \Intervention\Image\Image
     */
    public function initFromImagick(\Imagick $object)
    {
        throw new \Intervention\Image\Exception\NotSupportedException(
            "Gd driver is unable to init from Imagick object."
        );
    }

    /**
     * Initiates new image from binary data
     *
     * @param  string $data
     * @return \Intervention\Image\Image
     */
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
        $width = imagesx($resource);
        $height = imagesy($resource);

        // new canvas
        $canvas = imagecreatetruecolor($width, $height);

        // fill with transparent color
        imagealphablending($canvas, false);
        $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
        imagefilledrectangle($canvas, 0, 0, $width, $height, $transparent);
        imagecolortransparent($canvas, $transparent);
        imagealphablending($canvas, true);

        // copy original
        imagecopy($canvas, $resource, 0, 0, 0, 0, $width, $height);
        imagedestroy($resource);

        $resource = $canvas;

        return true;
    }
}
