<?php

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use Intervention\Image\Drivers\Abstract\Encoders\AbstractEncoder;
use Intervention\Image\Drivers\Imagick\Traits\CanReduceColors;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class BmpEncoder extends AbstractEncoder implements EncoderInterface
{
    use CanReduceColors;

    public function __construct(protected int $color_limit = 0)
    {
        //
    }

    public function encode(ImageInterface $image): EncodedImage
    {
        $format = 'bmp';
        $compression = Imagick::COMPRESSION_NO;

        $imagick = $image->frame()->core();
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);
        $this->maybeReduceColors($imagick, $this->color_limit);

        return new EncodedImage($imagick->getImagesBlob(), 'image/bmp');
    }
}
