<?php

namespace Intervention\Image\Drivers\Gd\Encoders;

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
        $image = $image->modify(new LimitColorsModifier($this->color_limit));
        $gd = $image->core()->native();
        $data = $this->getBuffered(function () use ($gd) {
            imagebmp($gd, null, false);
        });

        return new EncodedImage($data, 'image/bmp');
    }
}
