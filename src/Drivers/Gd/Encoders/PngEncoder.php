<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\PngEncoder as GenericPngEncoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class PngEncoder extends GenericPngEncoder implements SpecializedInterface
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $gd = $image->core()->native();
        $data = $this->buffered(function () use ($gd) {
            imageinterlace($gd, $this->interlaced);
            imagepng($gd, null, -1);
            imageinterlace($gd, false);
        });

        return new EncodedImage($data, 'image/png');
    }
}
