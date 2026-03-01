<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use ImagickException;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\PngEncoder as GenericPngEncoder;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Exceptions\FilePointerException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class PngEncoder extends GenericPngEncoder implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     *
     * @throws InvalidArgumentException
     * @throws FilePointerException
     * @throws StateException
     * @throws EncoderException
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        try {
            if ($this->indexed) {
                // reduce colors
                $output = clone $image;
                $output->reduceColors(256);

                $output = $output->core()->native();
                $output->setFormat('PNG');
                $output->setImageFormat('PNG');
            } else {
                $output = clone $image->core()->native();
                $output->setFormat('PNG32');
                $output->setImageFormat('PNG32');
            }

            $output->setCompression(Imagick::COMPRESSION_ZIP);
            $output->setImageCompression(Imagick::COMPRESSION_ZIP);

            if ($this->interlaced) {
                $output->setInterlaceScheme(Imagick::INTERLACE_LINE);
            }

            return new EncodedImage($output->getImagesBlob(), 'image/png');
        } catch (ImagickException $e) {
            throw new EncoderException('Failed to encode png format', previous: $e);
        }
    }
}
