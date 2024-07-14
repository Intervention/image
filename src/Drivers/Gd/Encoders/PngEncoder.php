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
        // use clone because colors may be reduced and original
        // image should not be altered by encoder
        $output = clone $image;

        if ($this->indexed) {
            $output->reduceColors(256)->core()->native();
        }

        $gd = $output->core()->native();
        $data = $this->buffered(function () use ($gd) {
            imageinterlace($gd, $this->interlaced);
            imagesavealpha($gd, true);
            imagepng($gd, null, -1);
        });

        return new EncodedImage($data, 'image/png');
    }
}
