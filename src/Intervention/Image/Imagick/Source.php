<?php

namespace Intervention\Image\Imagick;

use \Intervention\Image\Image;
use \Intervention\Image\Size;

class Source extends \Intervention\Image\AbstractSource
{
    public function initFromPath($path)
    {
        $core = new \Imagick;

        try {

            $core->readImage($path);
            $core->setImageType(\Imagick::IMGTYPE_TRUECOLORMATTE);

        } catch (\ImagickException $e) {
            throw new \Intervention\Image\Exception\NotReadableException(
                "Unable to read image from path ({$path})."
            );
        }

        // build image
        $image = $this->initFromImagick($core);
        $image->setFileInfoFromPath($path);

        return $image;
    }

    public function initFromGdResource($resource)
    {
        throw new \Intervention\Image\Exception\NotSupportedException(
            'Imagick driver is unable to init from GD resource.'
        );
    }

    public function initFromImagick($object)
    {
        return new Image(new Driver, $object);
    }

    public function initFromBinary($binary)
    {
        $core = new \Imagick;

        try {
            
            $core->readImageBlob($binary);

        } catch (\ImagickException $e) {
            throw new \Intervention\Image\Exception\NotReadableException(
                "Unable to read image from binary data."
            );
        }

        // build image
        $image = $this->initFromImagick($core);
        $image->mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $binary);

        return $image;
    }
}
