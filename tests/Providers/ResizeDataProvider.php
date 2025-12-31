<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Providers;

use Generator;
use Intervention\Image\Alignment;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Size;

class ResizeDataProvider
{
    public static function resizeDataProvider(): Generator
    {
        yield [new Size(300, 200), ['width' => 150], new Size(150, 200)];
        yield [new Size(300, 200), ['height' => 150], new Size(300, 150)];
        yield [new Size(300, 200), ['width' => 20, 'height' => 10], new Size(20, 10)];
        yield [new Size(300, 200), [], new Size(300, 200)];
    }

    public static function resizeDownDataProvider(): Generator
    {
        yield [new Size(800, 600), ['width' => 1000, 'height' => 2000], new Size(800, 600)];
        yield [new Size(800, 600), ['width' => 400, 'height' => 1000], new Size(400, 600)];
        yield [new Size(800, 600), ['width' => 1000, 'height' => 400], new Size(800, 400)];
        yield [new Size(800, 600), ['width' => 400, 'height' => 300], new Size(400, 300)];
        yield [new Size(800, 600), ['width' => 1000], new Size(800, 600)];
        yield [new Size(800, 600), ['height' => 1000], new Size(800, 600)];
        yield [new Size(800, 600), [], new Size(800, 600)];
    }

    public static function scaleDataProvider(): Generator
    {
        yield [new Size(800, 600), ['width' => 1000, 'height' => 2000], new Size(1000, 750)];
        yield [new Size(800, 600), ['width' => 2000, 'height' => 1000], new Size(1333, 1000)];
        yield [new Size(800, 600), ['height' => 3000], new Size(4000, 3000)];
        yield [new Size(800, 600), ['width' => 8000], new Size(8000, 6000)];
        yield [new Size(800, 600), ['width' => 100, 'height' => 400], new Size(100, 75)];
        yield [new Size(800, 600), ['width' => 400, 'height' => 100], new Size(133, 100)];
        yield [new Size(800, 600), ['height' => 300], new Size(400, 300)];
        yield [new Size(800, 600), ['width' => 80], new Size(80, 60)];
        yield [new Size(640, 480), ['width' => 225], new Size(225, 169)];
        yield [new Size(640, 480), ['width' => 223], new Size(223, 167)];
        yield [new Size(600, 800), ['width' => 300, 'height' => 300], new Size(225, 300)];
        yield [new Size(800, 600), ['width' => 400, 'height' => 10], new Size(13, 10)];
        yield [new Size(800, 600), ['width' => 1000, 'height' => 1200], new Size(1000, 750)];
        yield [new Size(12000, 12), ['width' => 4000, 'height' => 3000], new Size(4000, 4)];
        yield [new Size(12, 12000), ['width' => 4000, 'height' => 3000], new Size(3, 3000)];
        yield [new Size(12000, 6000), ['width' => 4000, 'height' => 3000], new Size(4000, 2000)];
        yield [new Size(3, 3000), ['height' => 300], new Size(1, 300)];
        yield [new Size(800, 600), [], new Size(800, 600)];
    }

    public static function scaleDownDataProvider(): Generator
    {
        yield [new Size(800, 600), ['width' => 1000, 'height' => 2000], new Size(800, 600)];
        yield [new Size(800, 600), ['width' => 1000, 'height' => 600], new Size(800, 600)];
        yield [new Size(800, 600), ['width' => 1000, 'height' => 300], new Size(400, 300)];
        yield [new Size(800, 600), ['width' => 400, 'height' => 1000], new Size(400, 300)];
        yield [new Size(800, 600), ['width' => 400], new Size(400, 300)];
        yield [new Size(800, 600), ['height' => 300], new Size(400, 300)];
        yield [new Size(800, 600), ['width' => 1000], new Size(800, 600)];
        yield [new Size(800, 600), ['height' => 1000], new Size(800, 600)];
        yield [new Size(800, 600), ['width' => 100], new Size(100, 75)];
        yield [new Size(800, 600), ['width' => 300, 'height' => 200], new Size(267, 200)];
        yield [new Size(600, 800), ['width' => 300, 'height' => 300], new Size(225, 300)];
        yield [new Size(800, 600), ['width' => 400, 'height' => 10], new Size(13, 10)];
        yield [new Size(3, 3000), ['height' => 300], new Size(1, 300)];
        yield [new Size(800, 600), [], new Size(800, 600)];
    }

    public static function coverDataProvider(): Generator
    {
        yield [new Size(800, 600), new Size(100, 100), new Size(133, 100)];
        yield [new Size(800, 600), new Size(200, 100), new Size(200, 150)];
        yield [new Size(800, 600), new Size(100, 200), new Size(267, 200)];
        yield [new Size(800, 600), new Size(2000, 10), new Size(2000, 1500)];
        yield [new Size(800, 600), new Size(10, 2000), new Size(2667, 2000)];
        yield [new Size(800, 600), new Size(800, 600), new Size(800, 600)];
        yield [new Size(400, 300), new Size(120, 120), new Size(160, 120)];
        yield [new Size(600, 800), new Size(100, 100), new Size(100, 133)];
        yield [new Size(100, 100), new Size(800, 600), new Size(800, 800)];
    }

    public static function containDataProvider(): Generator
    {
        yield [new Size(800, 600), new Size(100, 100), new Size(100, 75)];
        yield [new Size(800, 600), new Size(200, 100), new Size(133, 100)];
        yield [new Size(800, 600), new Size(100, 200), new Size(100, 75)];
        yield [new Size(800, 600), new Size(2000, 10), new Size(13, 10)];
        yield [new Size(800, 600), new Size(10, 2000), new Size(10, 8)];
        yield [new Size(800, 600), new Size(800, 600), new Size(800, 600)];
        yield [new Size(400, 300), new Size(120, 120), new Size(120, 90)];
        yield [new Size(600, 800), new Size(100, 100), new Size(75, 100)];
        yield [new Size(100, 100), new Size(800, 600), new Size(600, 600)];
    }

    public static function cropDataProvider(): Generator
    {
        yield [
            new Size(800, 600),
            new Size(100, 100),
            Alignment::CENTER,
            new Size(100, 100, new Point(350, 250))
        ];
        yield [
            new Size(800, 600),
            new Size(200, 100),
            Alignment::CENTER,
            new Size(200, 100, new Point(300, 250))
        ];
        yield [
            new Size(800, 600),
            new Size(100, 200),
            Alignment::CENTER,
            new Size(100, 200, new Point(350, 200))
        ];
        yield [
            new Size(800, 600),
            new Size(2000, 10),
            Alignment::CENTER,
            new Size(2000, 10, new Point(-600, 295))
        ];
        yield [
            new Size(800, 600),
            new Size(10, 2000),
            Alignment::CENTER,
            new Size(10, 2000, new Point(395, -700))
        ];
        yield [
            new Size(800, 600),
            new Size(800, 600),
            Alignment::CENTER,
            new Size(800, 600, new Point(0, 0))
        ];
        yield [
            new Size(400, 300),
            new Size(120, 120),
            Alignment::CENTER,
            new Size(120, 120, new Point(140, 90))
        ];
        yield [
            new Size(600, 800),
            new Size(100, 100),
            Alignment::CENTER,
            new Size(100, 100, new Point(250, 350))
        ];
    }
}
