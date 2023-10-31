<?php

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\Abstract\Encoders\AbstractEncoder;
use Intervention\Image\Drivers\Gd\Modifiers\LimitColorsModifier;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class BmpEncoder extends AbstractEncoder implements EncoderInterface
{
    public function __construct(protected int $color_limit = 0)
    {
        //
    }

    public function encode(ImageInterface $image): EncodedImage
    {
        $image = $image->modify(new LimitColorsModifier($this->color_limit));
        $data = $this->getBuffered(function () use ($image) {
            imagebmp($image->frame()->core(), null, false);
        });

        return new EncodedImage($data, 'image/bmp');
    }
}
