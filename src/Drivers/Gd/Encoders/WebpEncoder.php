<?php

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\DriverEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property int $quality
 */
class WebpEncoder extends DriverEncoder
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $gd = $image->core()->native();
        $data = $this->getBuffered(function () use ($gd) {
            imagewebp($gd, null, $this->quality);
        });

        return new EncodedImage($data, 'image/webp');
    }
}
