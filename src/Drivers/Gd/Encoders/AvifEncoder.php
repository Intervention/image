<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\DriverSpecializedEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property int $quality
 */
class AvifEncoder extends DriverSpecializedEncoder
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $gd = $image->core()->native();
        $data = $this->getBuffered(function () use ($gd) {
            imageavif($gd, null, $this->quality);
        });

        return new EncodedImage($data, 'image/avif');
    }
}
