<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use ImagickException;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\PngEncoder as GenericPngEncoder;
use Intervention\Image\Exceptions\AnimationException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class PngEncoder extends GenericPngEncoder implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImage
    {
        $output = $this->prepareOutput($image);

        $output->setCompression(Imagick::COMPRESSION_ZIP);
        $output->setImageCompression(Imagick::COMPRESSION_ZIP);

        if ($this->interlaced) {
            $output->setInterlaceScheme(Imagick::INTERLACE_LINE);
        }

        return new EncodedImage($output->getImagesBlob(), 'image/png');
    }

    /**
     * Prepare given image instance for PNG format output according to encoder settings
     *
     * @param ImageInterface $image
     * @throws AnimationException
     * @throws RuntimeException
     * @throws ColorException
     * @throws ImagickException
     * @return Imagick
     */
    private function prepareOutput(ImageInterface $image): Imagick
    {
        $output = clone $image;

        if ($this->indexed) {
            // reduce colors
            $output->reduceColors(256);

            $output = $output->core()->native();

            $output->setFormat('PNG');
            $output->setImageFormat('PNG');

            return $output;
        }

        // ensure to encode PNG image type 6 (true color alpha)
        $output = clone $image->core()->native();
        $output->setFormat('PNG32');
        $output->setImageFormat('PNG32');

        return $output;
    }
}
