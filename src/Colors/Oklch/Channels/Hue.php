<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Oklch\Channels;

use Intervention\Image\Colors\FloatColorChannel;

class Hue extends FloatColorChannel
{
    public static function min(): float
    {
        return 0;
    }

    public static function max(): float
    {
        return 360;
    }
}
