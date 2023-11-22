<?php

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use Intervention\Image\Drivers\DriverEncoder;
use Intervention\Image\Modifiers\LimitColorsModifier;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\ImageInterface;

class PngEncoder extends DriverEncoder
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $format = 'png';
        $compression = Imagick::COMPRESSION_ZIP;

        $image = $image->modify(new LimitColorsModifier($this->color_limit));
        $imagick = $image->core()->frame()->native();
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);

        return new EncodedImage($imagick->getImagesBlob(), 'image/png');
    }
}
