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
        $imagick = $this->setFormat($imagick, $image->origin());
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
     * @param Origin $origin
     * @throws ImagickException
     * @return Imagick
     */
    private function setFormat(Imagick $imagick, Origin $origin): Imagick
    {
        switch (true) {
            case $this->indexed === false:
                $imagick->setFormat('PNG32');
                $imagick->setImageFormat('PNG32');
                break;

            case $this->indexed === true:
                $imagick->setFormat('PNG8');
                $imagick->setImageFormat('PNG8');
                break;

            default:
                $imagick->setFormat($origin->isIndexed() ? 'PNG8' : 'PNG32');
                $imagick->setImageFormat($origin->isIndexed() ? 'PNG8' : 'PNG32');
                break;
        }

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
        } else {
            $imagick->setImageInterlaceScheme(Imagick::INTERLACE_NO);
            $imagick->setInterlaceScheme(Imagick::INTERLACE_NO);
        }

        return $imagick;
    }
}
