<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Providers;

use Generator;

class ColorDataProvider
{
    public static function rgbArray(): Generator
    {
        yield [[255, 0, 55], [255, 0, 55, 255]];
    }

    public static function rgbString(): Generator
    {
        yield [['rgb(255, 0, 55)'], [255, 0, 55, 255]];
        yield [['rgb(255, 0, 55, 1)'], [255, 0, 55, 255]];
    }
}
