<?php

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use Intervention\Image\Drivers\Abstract\Encoders\AbstractEncoder;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Drivers\Imagick\Modifiers\LimitColorsModifier;
use Intervention\Image\EncodedImage;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanCheckType;

class GifEncoder extends AbstractEncoder implements EncoderInterface
{
    use CanCheckType;

    public function __construct(protected int $color_limit = 0)
    {
        //
    }

    public function encode(ImageInterface $image): EncodedImage
    {
        $format = 'gif';
        $compression = Imagick::COMPRESSION_LZW;

        if (!is_a($image, Image::class)) {
            throw new EncoderException('Image does not match the current driver.');
        }

        $image = $this->failIfNotClass($image, Image::class);

        $image = $image->modify(new LimitColorsModifier($this->color_limit));
        $imagick = $image->getImagick();
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);

        return new EncodedImage($imagick->getImagesBlob(), 'image/gif');
    }
}
