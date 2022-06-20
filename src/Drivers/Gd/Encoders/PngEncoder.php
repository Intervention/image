<?php

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\Abstract\Encoders\AbstractEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class PngEncoder extends AbstractEncoder implements EncoderInterface
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $data = $this->getBuffered(function () use ($image) {
            imagepng($image->getFrame()->getCore(), null, -1);
        });

        return new EncodedImage($data, 'image/png');
    }
}
