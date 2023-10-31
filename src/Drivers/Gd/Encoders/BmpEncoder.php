<?php

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\Abstract\Encoders\AbstractEncoder;
use Intervention\Image\Drivers\Gd\Traits\CanReduceColors;
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
        $gd = $this->maybeReduceColors($image->frame()->core(), $this->color_limit);
        $data = $this->getBuffered(function () use ($gd) {
            imagebmp($gd, null, false);
        });

        return new EncodedImage($data, 'image/bmp');
    }
}
