<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use ImagickException;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\PngEncoder as GenericPngEncoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class PngEncoder extends GenericPngEncoder implements SpecializedInterface
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $imagick = $image->core()->native();
        $imagick = $this->setFormat($imagick);
        $imagick = $this->setCompression($imagick);
        $imagick = $this->setInterlaced($imagick);

        return new EncodedImage($imagick->getImagesBlob(), 'image/png');
    }

    /**
     * Set compression type on imagick output
     *
     * @param Imagick $imagick
     * @throws ImagickException
     * @return Imagick
     */
    private function setCompression(Imagick $imagick): Imagick
    {
        $imagick->setCompression(Imagick::COMPRESSION_ZIP);
        $imagick->setImageCompression(Imagick::COMPRESSION_ZIP);

        return $imagick;
    }

    /**
     * Set format according to encoder settings on imagick output
     *
     * @param Imagick $imagick
     * @throws ImagickException
     * @return Imagick
     */
    private function setFormat(Imagick $imagick): Imagick
    {
        $imagick->setFormat('PNG32');
        $imagick->setImageFormat('PNG32');

        return $imagick;
    }

    /**
     * Set interlace mode on imagick output according to encoder settings
     *
     * @param Imagick $imagick
     * @throws ImagickException
     * @return Imagick
     */
    private function setInterlaced(Imagick $imagick): Imagick
    {
        if ($this->interlaced) {
            $imagick->setImageInterlaceScheme(Imagick::INTERLACE_LINE);
            $imagick->setInterlaceScheme(Imagick::INTERLACE_LINE);
        }

        return $imagick;
    }
}
