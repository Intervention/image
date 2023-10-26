<?php

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\Abstract\Encoders\AbstractEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class AvifEncoder extends AbstractEncoder implements EncoderInterface
{
    public function __construct(int $quality)
    {
        $this->quality = $quality;
    }

    public function encode(ImageInterface $image): EncodedImage
    {
        $data = $this->getBuffered(function () use ($image) {
            imageavif($image->frame()->core(), null, $this->quality);
        });

        return new EncodedImage($data, 'image/avif');
    }
}
