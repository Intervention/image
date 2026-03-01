<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\GifEncoder as GenericGifEncoder;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Exceptions\FilePointerException;
use Intervention\Image\Exceptions\ImageException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;

class GifEncoder extends GenericGifEncoder implements SpecializedInterface
{
    /**
     * @throws InvalidArgumentException
     * @throws FilePointerException
     * @throws StateException
     * @throws EncoderException
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $format = 'GIF';
        $compression = Imagick::COMPRESSION_LZW;

        try {
            $imagick = $image->core()->native();
            $imagick->setFormat($format);
            $imagick->setImageFormat($format);
            $imagick->setCompression($compression);
            $imagick->setImageCompression($compression);

            if ($this->interlaced) {
                $imagick->setInterlaceScheme(Imagick::INTERLACE_LINE);
            }

            return new EncodedImage($imagick->getImagesBlob(), 'image/gif');
        } catch (ImageException $e) {
            throw new EncoderException('Failed to encode gif format', previous: $e);
        }
    }
}
