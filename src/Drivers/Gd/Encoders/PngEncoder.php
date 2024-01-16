<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\DriverSpecializedEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\ImageInterface;

class PngEncoder extends DriverSpecializedEncoder
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $data = $this->getBuffered(function () use ($image) {
            imagepng($image->core()->native(), null, -1);
        });

        return new EncodedImage($data, 'image/png');
    }
}
