<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use Intervention\Image\Drivers\DriverSpecializedEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\ImageInterface;

class BmpEncoder extends DriverSpecializedEncoder
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $format = 'bmp';
        $compression = Imagick::COMPRESSION_NO;

        $imagick = $image->core()->native();
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);

        return new EncodedImage($imagick->getImagesBlob(), 'image/bmp');
    }
}
