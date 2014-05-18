<?php

namespace Intervention\Image\Imagick;

use \Intervention\Image\Image;
use \Intervention\Image\Size;

class Source extends \Intervention\Image\AbstractSource
{
    /**
     * Initiates new image from path in filesystem
     *
     * @param  string $path
     * @return Intervention\Image\Image
     */
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

    /**
     * Initiates new image from GD resource
     *
     * @param  Resource $resource
     * @return Intervention\Image\Image
     */
    public function initFromGdResource($resource)
    {
        throw new \Intervention\Image\Exception\NotSupportedException(
            'Imagick driver is unable to init from GD resource.'
        );
    }

    /**
     * Initiates new image from Imagick object
     *
     * @param  Imagick $object
     * @return Intervention\Image\Image
     */
    public function initFromImagick(\Imagick $object)
    {
        return new Image(new Driver, $object);
    }

    /**
     * Initiates new image from binary data
     *
     * @param  string $data
     * @return Intervention\Image\Image
     */
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
