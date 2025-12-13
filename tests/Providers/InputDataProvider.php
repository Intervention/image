<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Providers;

use Generator;
use Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\Resource;

final class InputDataProvider
{
    public static function handleImageInputDataProvider(): Generator
    {
        yield [
            Resource::create('test.jpg')->path(),
            null,
            ImageInterface::class,
        ];

        yield [
            Resource::create('test.jpg')->data(),
            null,
            ImageInterface::class,
        ];
    }

    public static function handleColorInputDataProvider(): Generator
    {
        yield [
            'ffffff',
            null,
            ColorInterface::class,
        ];

        yield [
            'ffffff',
            [new HexColorDecoder()],
            ColorInterface::class,
        ];

        yield [
            'ffffff',
            [HexColorDecoder::class],
            ColorInterface::class,
        ];
    }
}
