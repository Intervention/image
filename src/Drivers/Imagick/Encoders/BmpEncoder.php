<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\BmpEncoder as GenericBmpEncoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class BmpEncoder extends GenericBmpEncoder implements SpecializedInterface
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $format = 'BMP';
        $compression = Imagick::COMPRESSION_NO;

        $imagick = $image->core()->native();
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);

        return new EncodedImage($imagick->getImagesBlob(), 'image/bmp');
    }
}
