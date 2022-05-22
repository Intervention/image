<?php

namespace Intervention\Image\Drivers\Imagick\Decoders;

use ImagickPixel;
use ImagickPixelException;
use Intervention\Image\Drivers\Abstract\Decoders\AbstractDecoder;
use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class RgbStringColorDecoder extends AbstractDecoder implements DecoderInterface
{
    public function decode($input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        if (substr($input, 0, 3) !== 'rgb') {
            throw new DecoderException('Unable to decode input');
        }

        try {
            $pixel = new ImagickPixel($input);
        } catch (ImagickPixelException $e) {
            throw new DecoderException('Unable to decode input');
        }

        return new Color($pixel);
    }
}
