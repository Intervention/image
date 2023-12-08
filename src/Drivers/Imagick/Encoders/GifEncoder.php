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
class GifEncoder extends DriverSpecializedEncoder
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $image = $image->modify(new LimitColorsModifier($this->color_limit));


        $format = 'gif';
        $compression = Imagick::COMPRESSION_LZW;

        $imagick = $image->core()->native();

        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);

        return new EncodedImage($imagick->getImagesBlob(), 'image/gif');
    }
}
