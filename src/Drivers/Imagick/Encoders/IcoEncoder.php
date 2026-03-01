<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use ImagickException;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\IcoEncoder as GenericIcoEncoder;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Exceptions\FilePointerException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;

class IcoEncoder extends GenericIcoEncoder implements SpecializedInterface
{
    /**
     * @throws InvalidArgumentException
     * @throws FilePointerException
     * @throws StateException
     * @throws EncoderException
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $format = 'ICO';
        $compression = Imagick::COMPRESSION_NO;

        try {
            $imagick = $image->core()->native();
            $imagick->setFormat($format);
            $imagick->setImageFormat($format);
            $imagick->setCompression($compression);
            $imagick->setImageCompression($compression);

            return new EncodedImage($imagick->getImagesBlob(), 'image/x-icon');
        } catch (ImagickException $e) {
            throw new EncoderException('Failed to encode ico format', previous: $e);
        }
    }
}
