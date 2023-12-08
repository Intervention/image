<?php

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use Intervention\Image\Drivers\DriverSpecializedEncoder;
use Intervention\Image\Modifiers\LimitColorsModifier;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property int $color_limit
 */
class BmpEncoder extends DriverSpecializedEncoder
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $format = 'bmp';
        $compression = Imagick::COMPRESSION_NO;

        $image = $image->modify(new LimitColorsModifier($this->color_limit));
        $imagick = $image->core()->native();
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);

        return new EncodedImage($imagick->getImagesBlob(), 'image/bmp');
    }
}
