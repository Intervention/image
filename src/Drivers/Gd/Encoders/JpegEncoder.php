<?php

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\Abstract\Encoders\AbstractEncoder;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class JpegEncoder extends AbstractEncoder implements EncoderInterface
{
    public function encode(ImageInterface $image): string
    {
        return $this->getBuffered(function () use ($image) {
            imagejpeg($image->getFrames()->first()->getCore(), null, $this->quality);
        });
    }
}
