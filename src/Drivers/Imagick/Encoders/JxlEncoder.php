<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Encoders;

use ImagickException;
use Intervention\Image\Drivers\Imagick\Modifiers\StripMetaModifier;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\JxlEncoder as GenericJxlEncoder;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Exceptions\StreamException;
use Intervention\Image\Exceptions\ImageException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;

class JxlEncoder extends GenericJxlEncoder implements SpecializedInterface
{
    /**
     * @throws InvalidArgumentException
     * @throws StreamException
     * @throws StateException
     * @throws EncoderException
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $format = 'JXL';

        // strip meta data
        if ($this->strip || (is_null($this->strip) && $this->driver()->config()->strip)) {
            $image->modify(new StripMetaModifier());
        }

        // In ImageMagick, JXL lossless encoding is triggered by a compression quality of 100.
        $quality = $this->lossless ? 100 : $this->quality;

        try {
            $imagick = clone $image->core()->native();
            $imagick->setFormat($format);
            $imagick->setImageFormat($format);
            $imagick->setCompressionQuality($quality);
            $imagick->setImageCompressionQuality($quality);

            $result = new EncodedImage($imagick->getImagesBlob(), 'image/jxl');
            $imagick->clear();

            return $result;
        } catch (ImagickException | ImageException $e) {
            throw new EncoderException('Failed to encode jxl format', previous: $e);
        }
    }
}
