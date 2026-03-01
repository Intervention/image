<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use ImagickException;
use Intervention\Image\Drivers\Imagick\Modifiers\StripMetaModifier;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\Jpeg2000Encoder as GenericJpeg2000Encoder;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Exceptions\FilePointerException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;

class Jpeg2000Encoder extends GenericJpeg2000Encoder implements SpecializedInterface
{
    /**
     * @throws InvalidArgumentException
     * @throws FilePointerException
     * @throws StateException
     * @throws EncoderException
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $format = 'JP2';
        $compression = Imagick::COMPRESSION_JPEG;

        // strip meta data
        if ($this->strip || (is_null($this->strip) && $this->driver()->config()->strip)) {
            $image->modify(new StripMetaModifier());
        }

        try {
            $imagick = $image->core()->native();
            $imagick->setImageBackgroundColor('white');
            $imagick->setBackgroundColor('white');
            $imagick->setFormat($format);
            $imagick->setImageFormat($format);
            $imagick->setCompression($compression);
            $imagick->setImageCompression($compression);
            $imagick->setCompressionQuality($this->quality);
            $imagick->setImageCompressionQuality($this->quality);

            return new EncodedImage($imagick->getImagesBlob(), 'image/jp2');
        } catch (ImagickException $e) {
            throw new EncoderException('Failed to encode jp2 format', previous: $e);
        }
    }
}
