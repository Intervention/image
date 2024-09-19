<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use ImagickException;
use Intervention\Image\Encoders\PngEncoder as GenericPngEncoder;
use Intervention\Image\Exceptions\AnimationException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class PngEncoder extends GenericPngEncoder implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $output = $this->prepareOutput($image);

        $output->setCompression(Imagick::COMPRESSION_ZIP);
        $output->setImageCompression(Imagick::COMPRESSION_ZIP);

        if ($this->interlaced) {
            $output->setInterlaceScheme(Imagick::INTERLACE_LINE);
        }

        return $this->createEncodedImage(function ($pointer) use ($output) {
            $output->writeImageFile($pointer, $this->format());
        });
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

            return $output;
        }

        $output = clone $image->core()->native();

        return $output;
    }

    private function format(): string
    {
        return match ($this->indexed) {
            true => 'PNG',
            false => 'PNG32', // ensure to encode PNG image type 6 (true color alpha)
        };
    }
}
