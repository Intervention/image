<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use ImagickException;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\PngEncoder as GenericPngEncoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Origin;

class PngEncoder extends GenericPngEncoder implements SpecializedInterface
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $imagick = clone $image->core()->native();
        $imagick->setCompression(Imagick::COMPRESSION_ZIP);
        $imagick->setImageCompression(Imagick::COMPRESSION_ZIP);

        $imagick = $this->setInterlaced($imagick);
        $imagick = $this->setIndexed($imagick, $image->origin());

        return new EncodedImage($imagick->getImagesBlob(), 'image/png');
    }

    /**
     * Set interlace mode on given imagick object according to encoder settings
     *
     * @param Imagick $imagick
     * @return Imagick
     * @throws ImagickException
     */
    private function setInterlaced(Imagick $imagick): Imagick
    {
        switch ($this->interlaced) {
            case true:
                $imagick->setInterlaceScheme(Imagick::INTERLACE_LINE);
                break;

            case false:
                $imagick->setInterlaceScheme(Imagick::INTERLACE_NO);
                break;
        }

        return $imagick;
    }

    /**
     * Set indexed color mode on given imagick object according to encoder settings
     *
     * @param Imagick $imagick
     * @return Imagick
     */
    private function setIndexed(Imagick $imagick, Origin $origin): Imagick
    {
        if ($this->indexed === true) {
            $imagick->setFormat('PNG8');
            $imagick->setImageFormat('PNG8');
            $imagick->quantizeImage(
                256,
                $imagick->getImageColorspace(),
                0,
                false,
                false
            );
        } elseif ($this->indexed === false) {
            $imagick->setFormat('PNG32');
            $imagick->setImageFormat('PNG32');
        } elseif ($origin->indexed() === true) {
            $imagick->setFormat('PNG8');
            $imagick->setImageFormat('PNG8');
            $imagick->quantizeImage(
                256,
                $imagick->getImageColorspace(),
                0,
                false,
                false
            );
        } elseif ($origin->indexed() === false) {
            $imagick->setFormat('PNG32');
            $imagick->setImageFormat('PNG32');
        } else {
            $imagick->setFormat('PNG');
            $imagick->setImageFormat('PNG');
        }

        return $imagick;
    }
}
