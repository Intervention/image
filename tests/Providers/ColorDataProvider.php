<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Providers;

use Generator;

class ColorDataProvider
{
    public static function rgbArray(): Generator
    {
        yield [[255, 0, 55], [255, 0, 55, 1]];
        yield [[255, 0, 55, 1], [255, 0, 55, 1]];
    }

    public static function rgbString(): Generator
    {
        yield [['rgb(255, 0, 55)'], [255, 0, 55, 1]];
        yield [['rgb(255, 0, 55, 1)'], [255, 0, 55, 1]];
        yield [['rgba(255, 0, 55)'], [255, 0, 55, 1]];
        yield [['rgba(255, 0, 55, 1)'], [255, 0, 55, 1]];
        yield [['srgba(255, 0, 55)'], [255, 0, 55, 1]];
        yield [['srgba(255, 0, 55, 1)'], [255, 0, 55, 1]];
        yield [['srgb(255, 0, 55)'], [255, 0, 55, 1]];
        yield [['srgb(255, 0, 55, 1)'], [255, 0, 55, 1]];
        yield [['rgb(255 0 55)'], [255, 0, 55, 1]];
        yield [['rgb(255 0 55 1)'], [255, 0, 55, 1]];
        yield [['rgba(255 0 55)'], [255, 0, 55, 1]];
        yield [['rgba(255 0 55 1)'], [255, 0, 55, 1]];
        yield [['srgba(255 0 55)'], [255, 0, 55, 1]];
        yield [['srgba(255 0 55 1)'], [255, 0, 55, 1]];
        yield [['srgb(255 0 55)'], [255, 0, 55, 1]];
        yield [['srgb(255 0 55 1)'], [255, 0, 55, 1]];
        yield [['rgb (255, 0, 55)'], [255, 0, 55, 1]];
        yield [['rgb (255 0 55)'], [255, 0, 55, 1]];
    }

    public static function rgbHex(): Generator
    {
        yield [['ff5500'], [255, 85, 0, 1]];
        yield [['cccccc'], [204, 204, 204, 1]];
        yield [['ff5500cc'], [255, 85, 0, .8]];
        yield [['cccccccc'], [204, 204, 204, .8]];
        yield [['#ff5500'], [255, 85, 0, 1]];
        yield [['#cccccc'], [204, 204, 204, 1]];
        yield [['#ff5500cc'], [255, 85, 0, .8]];
        yield [['#cccccccc'], [204, 204, 204, .8]];
        yield [['f50'], [255, 85, 0, 1]];
        yield [['ccc'], [204, 204, 204, 1]];
        yield [['f50c'], [255, 85, 0, .8]];
        yield [['cccc'], [204, 204, 204, .8]];
        yield [['#f50'], [255, 85, 0, 1]];
        yield [['#ccc'], [204, 204, 204, 1]];
        yield [['#f50c'], [255, 85, 0, .8]];
        yield [['#cccc'], [204, 204, 204, .8]];
    }

    public static function rgbColorname(): Generator
    {
        yield [['tomato'], [255, 99, 71, 1]];
        yield [['steelblue'], [70, 130, 180, 1]];
    }

    public static function cmykArray(): Generator
    {
        yield [[0, 0, 0, 0], [0, 0, 0, 0]];
        yield [[0, 100, 100, 0], [0, 100, 100, 0]];
    }

    public static function cmykString(): Generator
    {
        yield [['cmyk(0,0,0,0)'], [0, 0, 0, 0]];
        yield [['cmyk(0, 100, 100, 0)'], [0, 100, 100, 0]];
        yield [['cmyk(0%, 100%, 100%, 0%)'], [0, 100, 100, 0]];
        yield [['cmyk(0 0 0 0)'], [0, 0, 0, 0]];
        yield [['cmyk(0 100 100 0)'], [0, 100, 100, 0]];
        yield [['cmyk(0% 100% 100% 0%)'], [0, 100, 100, 0]];
        yield [['CMYK(0 0 0 10)'], [0, 0, 0, 10]];
        yield [['cmyk (0, 100, 100, 0)'], [0, 100, 100, 0]];
        yield [['cmyk (0 100 100 0)'], [0, 100, 100, 0]];
    }

    public static function hslArray(): Generator
    {
        yield [[0, 0, 0], [0, 0, 0]];
        yield [[0, 100, 50], [0, 100, 50]];
    }

    public static function hslString(): Generator
    {
        yield [['hsl(0,0,0)'], [0, 0, 0]];
        yield [['hsl(0, 100, 50)'], [0, 100, 50]];
        yield [['hsl(360, 100, 50)'], [360, 100, 50]];
        yield [['hsl(180, 100%, 50%)'], [180, 100, 50]];
        yield [['hsl(0 0 0)'], [0, 0, 0]];
        yield [['hsl(0 100 50)'], [0, 100, 50]];
        yield [['hsl(360 100 50)'], [360, 100, 50]];
        yield [['hsl(180 100% 50%)'], [180, 100, 50]];
        yield [['hsl (0, 100, 50)'], [0, 100, 50]];
        yield [['hsl (0 100 50)'], [0, 100, 50]];
    }

    public static function hsvArray(): Generator
    {
        yield [[0, 0, 0], [0, 0, 0]];
        yield [[0, 100, 50], [0, 100, 50]];
    }

    public static function hsvString(): Generator
    {
        yield [['hsv(0,0,0)'], [0, 0, 0]];
        yield [['hsv(0, 100, 50)'], [0, 100, 50]];
        yield [['hsv(360, 100, 50)'], [360, 100, 50]];
        yield [['hsv(180, 100%, 50%)'], [180, 100, 50]];
        yield [['hsv(0 0 0)'], [0, 0, 0]];
        yield [['hsv(0 100 50)'], [0, 100, 50]];
        yield [['hsv(360 100 50)'], [360, 100, 50]];
        yield [['hsv(180 100% 50%)'], [180, 100, 50]];
        yield [['hsv (360, 100, 50)'], [360, 100, 50]];
        yield [['hsv (360 100 50)'], [360, 100, 50]];
    }

    public static function oklabArray(): Generator
    {
        yield [[0.0, 0.0, 0.0], [0.0, 0.0, 0.0]];
        yield [[1.0, 0.0, 0.0], [1.0, 0.0, 0.0]];
        yield [[.49, .04, .2], [.49, .04, .2]];
        yield [[.49, .04, .2], [.49, .04, .2]];
    }

    public static function oklabString(): Generator
    {
        yield [['oklab(0, 0, 0)'], [0.0, 0.0, 0.0]];
        yield [['oklab(1, 0, 0)'], [1.0, 0.0, 0.0]];
        yield [['oklab(0.54, 0, 0)'], [.54, 0.0, 0.0]];
        yield [['oklab(.54, 0, 0)'], [.54, 0.0, 0.0]];
        yield [['oklab(.548, 0, 0)'], [.548, 0.0, 0.0]];
        yield [['oklab(.54, 0.3, 0.2)'], [.54, .3, .2]];
        yield [['oklab(.54, -0.3, -0.2)'], [.54, -.3, -.2]];
        yield [['oklab(.54, .257, -.199)'], [.54, .257, -.199]];
        yield [['oklab(25%, .257, -.199)'], [.25, .257, -.199]];
        yield [['oklab(100%, .257, -.199)'], [1.0, .257, -.199]];
        yield [['oklab(0%, .257, -.199)'], [0.0, .257, -.199]];
        yield [['oklab(0%, 25%, -50%)'], [0.0, .1, -.2]];
        yield [['oklab(.49, -20%, -50%)'], [.49, -.08, -.2]];
        yield [['oklab (.49, -20%, 50%)'], [.49, -.08, .2]];
        yield [['oklab (.49,-20%,50%)'], [.49, -.08, .2]];
        yield [['oklab(.49,-20%,50%)'], [.49, -.08, .2]];
        yield [['oklab(.49,10%,50%)'], [.49, .04, .2]];
        yield [['oklab(.49 10% 50%)'], [.49, .04, .2]];
        yield [['oklab(59.69% 0.1007 0.1191)'], [.5969, .1007, .1191]];
        yield [['oklab(59.69% 100.00% -50%)'], [.5969, .4, -.2]];
        yield [['oklab (0.54, 0, 0)'], [.54, 0.0, 0.0]];
        yield [['oklab (0.54 0 0)'], [.54, 0.0, 0.0]];
    }

    public static function oklchArray(): Generator
    {
        yield [[0.0, 0.0, 0], [0.0, 0.0, 0.0]];
        yield [[1.0, 0.0, 0], [1.0, 0.0, 0.0]];
        yield [[.49, .04, 200], [.49, .04, 200]];
        yield [[.49, -0.04, 360], [.49, -0.04, 360]];
    }

    public static function oklchString(): Generator
    {
        yield [['oklch(0, 0, 0)'], [0.0, 0.0, 0]];
        yield [['oklch(50% 0.123 21.57)'], [0.5, 0.123, 21.57]];
        yield [['oklch (50% 0.123 21.57)'], [0.5, 0.123, 21.57]];
        yield [['oklch (50%, 0.123, 21.57)'], [0.5, 0.123, 21.57]];
        yield [['oklch (50%,0.123,21.57)'], [0.5, 0.123, 21.57]];
    }
}
