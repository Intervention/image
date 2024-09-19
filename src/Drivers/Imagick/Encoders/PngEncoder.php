<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use Intervention\Image\Encoders\PngEncoder as GenericPngEncoder;
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
        if ($this->indexed) {
            // reduce colors
            $output = clone $image;
            $output->reduceColors(256);

            $output = $output->core()->native();
        } else {
            $output = clone $image->core()->native();
        }

        $output->setCompression(Imagick::COMPRESSION_ZIP);
        $output->setImageCompression(Imagick::COMPRESSION_ZIP);

        if ($this->interlaced) {
            $output->setInterlaceScheme(Imagick::INTERLACE_LINE);
        }

        return $this->createEncodedImage(function ($pointer) use ($output) {
            $output->writeImageFile(
                $pointer,
                $this->indexed ? 'PNG' : 'PNG32',
            );
        });
    }
}
