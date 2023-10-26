<?php

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use Intervention\Image\Drivers\Abstract\Encoders\AbstractEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class AvifEncoder extends AbstractEncoder implements EncoderInterface
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $format = 'AVIF';
        $compression = Imagick::COMPRESSION_ZIP;

        $imagick = $image->frame()->core();
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);

        return new EncodedImage($imagick->getImagesBlob(), 'image/avif');
    }
}
