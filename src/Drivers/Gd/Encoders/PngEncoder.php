<?php

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\DriverSpecializedEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Modifiers\LimitColorsModifier;

/**
 * @property int $color_limit
 */
class PngEncoder extends DriverSpecializedEncoder
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $image = $image->modify(new LimitColorsModifier($this->color_limit));
        $gd = $image->core()->native();
        $data = $this->getBuffered(function () use ($gd) {
            imagepng($gd, null, -1);
        });

        return new EncodedImage($data, 'image/png');
    }
}
