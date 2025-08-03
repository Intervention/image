<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Providers;

use Generator;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;

class ResizeDataProvider
{
    public static function resizeDataProvider(): Generator
    {
        yield [new Rectangle(300, 200), ['width' => 150], new Rectangle(150, 200)];
        yield [new Rectangle(300, 200), ['height' => 150], new Rectangle(300, 150)];
        yield [new Rectangle(300, 200), ['width' => 20, 'height' => 10], new Rectangle(20, 10)];
        yield [new Rectangle(300, 200), [], new Rectangle(300, 200)];
    }

    public static function resizeDownDataProvider(): Generator
    {
        yield [new Rectangle(800, 600), ['width' => 1000, 'height' => 2000], new Rectangle(800, 600)];
        yield [new Rectangle(800, 600), ['width' => 400, 'height' => 1000], new Rectangle(400, 600)];
        yield [new Rectangle(800, 600), ['width' => 1000, 'height' => 400], new Rectangle(800, 400)];
        yield [new Rectangle(800, 600), ['width' => 400, 'height' => 300], new Rectangle(400, 300)];
        yield [new Rectangle(800, 600), ['width' => 1000], new Rectangle(800, 600)];
        yield [new Rectangle(800, 600), ['height' => 1000], new Rectangle(800, 600)];
        yield [new Rectangle(800, 600), [], new Rectangle(800, 600)];
    }

    public static function scaleDataProvider(): Generator
    {
        yield [new Rectangle(800, 600), ['width' => 1000, 'height' => 2000], new Rectangle(1000, 750)];
        yield [new Rectangle(800, 600), ['width' => 2000, 'height' => 1000], new Rectangle(1333, 1000)];
        yield [new Rectangle(800, 600), ['height' => 3000], new Rectangle(4000, 3000)];
        yield [new Rectangle(800, 600), ['width' => 8000], new Rectangle(8000, 6000)];
        yield [new Rectangle(800, 600), ['width' => 100, 'height' => 400], new Rectangle(100, 75)];
        yield [new Rectangle(800, 600), ['width' => 400, 'height' => 100], new Rectangle(133, 100)];
        yield [new Rectangle(800, 600), ['height' => 300], new Rectangle(400, 300)];
        yield [new Rectangle(800, 600), ['width' => 80], new Rectangle(80, 60)];
        yield [new Rectangle(640, 480), ['width' => 225], new Rectangle(225, 169)];
        yield [new Rectangle(640, 480), ['width' => 223], new Rectangle(223, 167)];
        yield [new Rectangle(600, 800), ['width' => 300, 'height' => 300], new Rectangle(225, 300)];
        yield [new Rectangle(800, 600), ['width' => 400, 'height' => 10], new Rectangle(13, 10)];
        yield [new Rectangle(800, 600), ['width' => 1000, 'height' => 1200], new Rectangle(1000, 750)];
        yield [new Rectangle(12000, 12), ['width' => 4000, 'height' => 3000], new Rectangle(4000, 4)];
        yield [new Rectangle(12, 12000), ['width' => 4000, 'height' => 3000], new Rectangle(3, 3000)];
        yield [new Rectangle(12000, 6000), ['width' => 4000, 'height' => 3000], new Rectangle(4000, 2000)];
        yield [new Rectangle(3, 3000), ['height' => 300], new Rectangle(1, 300)];
        yield [new Rectangle(800, 600), [], new Rectangle(800, 600)];
    }

    public static function scaleDownDataProvider(): Generator
    {
        yield [new Rectangle(800, 600), ['width' => 1000, 'height' => 2000], new Rectangle(800, 600)];
        yield [new Rectangle(800, 600), ['width' => 1000, 'height' => 600], new Rectangle(800, 600)];
        yield [new Rectangle(800, 600), ['width' => 1000, 'height' => 300], new Rectangle(400, 300)];
        yield [new Rectangle(800, 600), ['width' => 400, 'height' => 1000], new Rectangle(400, 300)];
        yield [new Rectangle(800, 600), ['width' => 400], new Rectangle(400, 300)];
        yield [new Rectangle(800, 600), ['height' => 300], new Rectangle(400, 300)];
        yield [new Rectangle(800, 600), ['width' => 1000], new Rectangle(800, 600)];
        yield [new Rectangle(800, 600), ['height' => 1000], new Rectangle(800, 600)];
        yield [new Rectangle(800, 600), ['width' => 100], new Rectangle(100, 75)];
        yield [new Rectangle(800, 600), ['width' => 300, 'height' => 200], new Rectangle(267, 200)];
        yield [new Rectangle(600, 800), ['width' => 300, 'height' => 300], new Rectangle(225, 300)];
        yield [new Rectangle(800, 600), ['width' => 400, 'height' => 10], new Rectangle(13, 10)];
        yield [new Rectangle(3, 3000), ['height' => 300], new Rectangle(1, 300)];
        yield [new Rectangle(800, 600), [], new Rectangle(800, 600)];
    }

    public static function coverDataProvider(): Generator
    {
        yield [new Rectangle(800, 600), new Rectangle(100, 100), new Rectangle(133, 100)];
        yield [new Rectangle(800, 600), new Rectangle(200, 100), new Rectangle(200, 150)];
        yield [new Rectangle(800, 600), new Rectangle(100, 200), new Rectangle(267, 200)];
        yield [new Rectangle(800, 600), new Rectangle(2000, 10), new Rectangle(2000, 1500)];
        yield [new Rectangle(800, 600), new Rectangle(10, 2000), new Rectangle(2667, 2000)];
        yield [new Rectangle(800, 600), new Rectangle(800, 600), new Rectangle(800, 600)];
        yield [new Rectangle(400, 300), new Rectangle(120, 120), new Rectangle(160, 120)];
        yield [new Rectangle(600, 800), new Rectangle(100, 100), new Rectangle(100, 133)];
        yield [new Rectangle(100, 100), new Rectangle(800, 600), new Rectangle(800, 800)];
    }

    public static function containDataProvider(): Generator
    {
        yield [new Rectangle(800, 600), new Rectangle(100, 100), new Rectangle(100, 75)];
        yield [new Rectangle(800, 600), new Rectangle(200, 100), new Rectangle(133, 100)];
        yield [new Rectangle(800, 600), new Rectangle(100, 200), new Rectangle(100, 75)];
        yield [new Rectangle(800, 600), new Rectangle(2000, 10), new Rectangle(13, 10)];
        yield [new Rectangle(800, 600), new Rectangle(10, 2000), new Rectangle(10, 8)];
        yield [new Rectangle(800, 600), new Rectangle(800, 600), new Rectangle(800, 600)];
        yield [new Rectangle(400, 300), new Rectangle(120, 120), new Rectangle(120, 90)];
        yield [new Rectangle(600, 800), new Rectangle(100, 100), new Rectangle(75, 100)];
        yield [new Rectangle(100, 100), new Rectangle(800, 600), new Rectangle(600, 600)];
    }

    public static function cropDataProvider(): Generator
    {
        yield [
            new Rectangle(800, 600),
            new Rectangle(100, 100),
            'center',
            new Rectangle(100, 100, new Point(350, 250))
        ];
        yield [
            new Rectangle(800, 600),
            new Rectangle(200, 100),
            'center',
            new Rectangle(200, 100, new Point(300, 250))
        ];
        yield [
            new Rectangle(800, 600),
            new Rectangle(100, 200),
            'center',
            new Rectangle(100, 200, new Point(350, 200))
        ];
        yield [
            new Rectangle(800, 600),
            new Rectangle(2000, 10),
            'center',
            new Rectangle(2000, 10, new Point(-600, 295))
        ];
        yield [
            new Rectangle(800, 600),
            new Rectangle(10, 2000),
            'center',
            new Rectangle(10, 2000, new Point(395, -700))
        ];
        yield [
            new Rectangle(800, 600),
            new Rectangle(800, 600),
            'center',
            new Rectangle(800, 600, new Point(0, 0))
        ];
        yield [
            new Rectangle(400, 300),
            new Rectangle(120, 120),
            'center',
            new Rectangle(120, 120, new Point(140, 90))
        ];
        yield [
            new Rectangle(600, 800),
            new Rectangle(100, 100),
            'center',
            new Rectangle(100, 100, new Point(250, 350))
        ];
    }
}
