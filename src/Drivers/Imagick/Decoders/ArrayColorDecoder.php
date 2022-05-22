<?php

namespace Intervention\Image\Drivers\Imagick\Decoders;

use ImagickPixel;
use Intervention\Image\Drivers\Abstract\Decoders\AbstractDecoder;
use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanValidateColors;

class ArrayColorDecoder extends AbstractDecoder implements DecoderInterface
{
    use CanValidateColors;

    public function decode($input): ImageInterface|ColorInterface
    {
        if (! $this->isValidColorArray($input)) {
            throw new DecoderException('Unable to decode input');
        }

        if (count($input) === 3) {
            $input[] = 1;
        }

        list($r, $g, $b, $a) = $input;

        $pixel = new ImagickPixel(
            sprintf('rgba(%d, %d, %d, %.2F)', $r, $g, $b, $a)
        );

        return new Color($pixel);
    }
}
