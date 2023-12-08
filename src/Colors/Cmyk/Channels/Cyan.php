<?php

namespace Intervention\Image\Colors\Cmyk\Channels;

use Intervention\Image\Colors\AbstractColorChannel;

class Cyan extends AbstractColorChannel
{
    public function min(): int
    {
        return 0;
    }

    public function max(): int
    {
        return 100;
    }
}
