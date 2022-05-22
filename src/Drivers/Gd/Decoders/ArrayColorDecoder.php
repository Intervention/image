<?php

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\Abstract\Decoders\AbstractDecoder;
use Intervention\Image\Drivers\Gd\Color;
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

        return new Color(
            ($this->opacityToGdAlpha($a) << 24) + ($r << 16) + ($g << 8) + $b
        );
    }

    protected function opacityToGdAlpha(float $opacity): int
    {
        return intval(round($opacity * 127 * -1 + 127));
    }
}
