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
        $image->mime = $this->getMime($core);
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
        $image->mime = $this->getMime($core);

        return $image;
    }

    private function getMime(\Imagick $core)
    {
        $info = $core->identifyImage(true);

        if (preg_match("/Mime type: (?P<mime>[-\w+]+\/[-\w+]+)/", $info['rawOutput'], $match)) {
            
            return $match['mime'];

        } else {
            throw new \Intervention\Image\Exception\InvalidImageTypeException(
                "Mime type could not be extracted from image."
            );
        }
    }
}
