<?php

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\Abstract\Decoders\AbstractDecoder;
use Intervention\Image\Drivers\Gd\Color;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanValidateColorArray;

class ArrayColorDecoder extends AbstractDecoder implements DecoderInterface
{
    use CanValidateColorArray;

    public function decode($input): ImageInterface|ColorInterface
    {
        if (! $this->isValidColorArray($input)) {
            $this->fail();
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
