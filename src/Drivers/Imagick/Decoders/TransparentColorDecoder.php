<?php

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanValidateColors;

class TransparentColorDecoder extends ArrayColorDecoder implements DecoderInterface
{
    public function decode($input): ImageInterface|ColorInterface
    {
        if (! is_string($input) || strtolower($input) !== 'transparent') {
            $this->fail();
        }

        return parent::decode([0, 0, 0, 0]);
    }
}
