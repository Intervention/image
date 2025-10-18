<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Providers;

use Generator;
use Intervention\Image\Colors\Rgb\Colorspace as RgbSpace;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykSpace;
use Intervention\Image\Format;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Resolution;
use Intervention\Image\Tests\Resource;

class ResourceProvider
{
    public static function baseData(): Generator
    {
        yield [
            'format' => Format::PNG,
            'resource' => new Resource('300dpi.png'),
            'size' => new Rectangle(200, 100),
            'colorspace' => RgbSpace::class,
            'resolution' => new Resolution(300, 300),
        ];

        yield [
            'format' => Format::PNG,
            'resource' => new Resource('150.dpi.png'),
            'size' => new Rectangle(32, 32),
            'colorspace' => RgbSpace::class,
            'resolution' => new Resolution(150, 150),
        ];

        yield [
            'format' => Format::JPEG,
            'resource' => new Resource('150.dpi.jpg'),
            'size' => new Rectangle(32, 32),
            'colorspace' => RgbSpace::class,
            'resolution' => new Resolution(150, 150),
        ];

        yield [
            'format' => Format::TIFF,
            'resource' => new Resource('150.dpi.tif'),
            'size' => new Rectangle(32, 32),
            'colorspace' => RgbSpace::class,
            'resolution' => new Resolution(150, 150),
        ];

        yield [
            'format' => Format::GIF,
            'resource' => new Resource('animation.gif'),
            'size' => new Rectangle(20, 15),
            'colorspace' => RgbSpace::class,
            'resolution' => new Resolution(0, 0),
        ];

        yield [
            'format' => Format::PNG,
            'resource' => new Resource('blocks.png'),
            'size' => new Rectangle(640, 480),
            'colorspace' => RgbSpace::class,
            'resolution' => new Resolution(72, 72),
        ];

        yield [
            'format' => Format::GIF,
            'resource' => new Resource('blue.gif'),
            'size' => new Rectangle(16, 16),
            'colorspace' => RgbSpace::class,
            'resolution' => new Resolution(0, 0),
        ];

        yield [
            'format' => Format::GIF,
            'resource' => new Resource('cats.gif'),
            'size' => new Rectangle(75, 50),
            'colorspace' => RgbSpace::class,
            'resolution' => new Resolution(0, 0),
        ];

        yield [
            'format' => Format::PNG,
            'resource' => new Resource('circle.png'),
            'size' => new Rectangle(50, 50),
            'colorspace' => RgbSpace::class,
            'resolution' => new Resolution(0, 0),
        ];

        yield [
            'format' => Format::JPEG,
            'resource' => new Resource('cmyk.jpg'),
            'size' => new Rectangle(12, 12),
            'colorspace' => CmykSpace::class,
            'resolution' => new Resolution(300, 300),
        ];

        yield [
            'format' => Format::JPEG,
            'resource' => new Resource('exif.jpg'),
            'size' => new Rectangle(16, 16),
            'colorspace' => RgbSpace::class,
            'resolution' => new Resolution(72, 72),
        ];

        yield [
            'format' => Format::GIF,
            'resource' => new Resource('gradient.gif'),
            'size' => new Rectangle(16, 16),
            'colorspace' => RgbSpace::class,
            'resolution' => new Resolution(0, 0),
        ];

        yield [
            'format' => Format::BMP,
            'resource' => new Resource('gradient.bmp'),
            'size' => new Rectangle(8, 8),
            'colorspace' => RgbSpace::class,
            'resolution' => new Resolution(72, 72),
        ];

        yield [
            'format' => Format::GIF,
            'resource' => new Resource('green.gif'),
            'size' => new Rectangle(16, 16),
            'colorspace' => RgbSpace::class,
            'resolution' => new Resolution(0, 0),
        ];

        yield [
            'format' => Format::JPEG,
            'resource' => new Resource('orientation.jpg'),
            'size' => new Rectangle(20, 30),
            'colorspace' => RgbSpace::class,
            'resolution' => new Resolution(72, 72),
        ];

        yield [
            'format' => Format::PNG,
            'resource' => new Resource('radial.png'),
            'size' => new Rectangle(50, 50),
            'colorspace' => RgbSpace::class,
            'resolution' => new Resolution(72, 72),
        ];

        yield [
            'format' => Format::GIF,
            'resource' => new Resource('red.gif'),
            'size' => new Rectangle(16, 16),
            'colorspace' => RgbSpace::class,
            'resolution' => new Resolution(0, 0),
        ];

        yield [
            'format' => Format::JPEG,
            'resource' => new Resource('test.jpg'),
            'size' => new Rectangle(320, 240),
            'colorspace' => RgbSpace::class,
            'resolution' => new Resolution(72, 72),
        ];

        yield [
            'format' => Format::PNG,
            'resource' => new Resource('tile.png'),
            'size' => new Rectangle(16, 16),
            'colorspace' => RgbSpace::class,
            'resolution' => new Resolution(0, 0),
        ];

        yield [
            'format' => Format::PNG,
            'resource' => new Resource('trim.png'),
            'size' => new Rectangle(50, 50),
            'colorspace' => RgbSpace::class,
            'resolution' => new Resolution(0, 0),
        ];
    }

    public static function resourceData(): Generator
    {
        foreach (static::baseData() as $yield) {
            yield [$yield['resource']];
        }
    }

    public static function sizeData(): Generator
    {
        foreach (static::baseData() as $yield) {
            yield [$yield['resource'], $yield['size']];
        }
    }

    public static function colorspaceData(): Generator
    {
        foreach (static::baseData() as $yield) {
            yield [$yield['resource'], $yield['colorspace']];
        }
    }

    public static function resolutionData(): Generator
    {
        foreach (static::baseData() as $yield) {
            yield [$yield['resource'], $yield['resolution']];
        }
    }
}
