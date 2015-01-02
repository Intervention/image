<?php

namespace Intervention\Image\Imagick;

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
        $imagick = new \Imagick;

        try {

            $imagick->readImage($path);
            $imagick->setImageType(\Imagick::IMGTYPE_TRUECOLORMATTE);

        } catch (\ImagickException $e) {
            throw new \Intervention\Image\Exception\NotReadableException(
                "Unable to read image from path ({$path})."
            );
        }

        // build image
        $image = $this->initFromImagick($imagick);
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
        throw new \Intervention\Image\Exception\NotSupportedException(
            'Imagick driver is unable to init from GD resource.'
        );
    }

    /**
     * Initiates new image from Imagick object
     *
     * @param  Imagick $imagick
     * @return \Intervention\Image\Image
     */
    public function initFromImagick(\Imagick $imagick)
    {
        // reset image orientation
        $imagick->setImageOrientation(\Imagick::ORIENTATION_UNDEFINED);

        // coalesce possible animation
        $imagick = $imagick->coalesceImages();

        return new Image(new Driver, new Container($imagick));
    }

    /**
     * Initiates new image from binary data
     *
     * @param  string $data
     * @return \Intervention\Image\Image
     */
    public function initFromBinary($binary)
    {
        $imagick = new \Imagick;

        try {

            $imagick->readImageBlob($binary);

        } catch (\ImagickException $e) {
            throw new \Intervention\Image\Exception\NotReadableException(
                "Unable to read image from binary data."
            );
        }

        // build image
        $image = $this->initFromImagick($imagick);
        $image->mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $binary);

        return $image;
    }

    /**
     * Turns object into one frame Imagick object
     * by removing all frames except first
     *
     * @param  Imagick $object
     * @return Imagick
     */
    private function removeAnimation(\Imagick $object)
    {
        $imagick = new \Imagick;

        foreach ($object as $frame) {
            $imagick->addImage($frame->getImage());
            break;
        }

        $object->destroy();

        return $imagick;
    }
}
