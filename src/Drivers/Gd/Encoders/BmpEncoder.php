<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\BmpEncoder as GenericBmpEncoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class BmpEncoder extends GenericBmpEncoder implements SpecializedInterface
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $data = $this->buffered(function () use ($image) {
            imagebmp($image->core()->native(), null, false);
        });

        return new EncodedImage($data, 'image/bmp');
    }
}
