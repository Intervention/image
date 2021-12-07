<?php

namespace Intervention\Image\Drivers\Imagick\Decoders;

use ImagickPixel;
use ImagickPixelException;
use Intervention\Image\Drivers\Abstract\Decoders\AbstractDecoder;
use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanValidateColors;

class RgbStringColorDecoder extends AbstractDecoder implements DecoderInterface
{
    public function decode($input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            $this->fail();
        }

        if (substr($input, 0, 3) !== 'rgb') {
            $this->fail();
        }

        try {
            $pixel = new ImagickPixel($input);
        } catch (ImagickPixelException $e) {
            $this->fail();
        }

        return new Color($pixel);
    }
}
