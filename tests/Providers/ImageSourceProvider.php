<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Providers;

use Generator;
use SplFileInfo;

class ImageSourceProvider
{
    public static function filePaths(): Generator
    {
        $basePath = __DIR__ . '/../resources';

        yield [realpath($basePath . '/animation.gif')];
        yield [realpath($basePath . '/blocks.png')];
        yield [realpath($basePath . '/blue.gif')];
        yield [realpath($basePath . '/cats.gif')];
        yield [realpath($basePath . '/circle.png')];
        yield [realpath($basePath . '/cmyk.jpg')];
        yield [realpath($basePath . '/exif.jpg')];
        yield [realpath($basePath . '/gradient.bmp')];
        yield [realpath($basePath . '/gradient.gif')];
        yield [realpath($basePath . '/green.gif')];
        yield [realpath($basePath . '/orientation.jpg')];
        yield [realpath($basePath . '/radial.png')];
        yield [realpath($basePath . '/red.gif')];
        yield [realpath($basePath . '/test.jpg')];
        yield [realpath($basePath . '/tile.png')];
        yield [realpath($basePath . '/trim.png')];
    }

    public static function binaryData(): Generator
    {
        foreach (static::filePaths() as $path) {
            yield [file_get_contents($path[0])];
        }
    }

    public static function splFileInfoObjects(): Generator
    {
        foreach (static::filePaths() as $path) {
            yield [new SplFileInfo($path[0])];
        }
    }

    public static function base64Data(): Generator
    {
        foreach (static::filePaths() as $path) {
            yield [base64_encode(file_get_contents($path[0]))];
        }
    }
}
