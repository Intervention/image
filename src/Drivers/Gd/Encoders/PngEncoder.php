<?php

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\DriverEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Modifiers\LimitColorsModifier;

class PngEncoder extends DriverEncoder
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
