<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Providers;

use Generator;
use Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\Traits\CanGenerateTestData;

final class InputDataProvider
{
    use CanGenerateTestData;

    public static function handleImageInputDataProvider(): Generator
    {
        yield [
            self::getTestResourcePath('test.jpg'),
            [],
            ImageInterface::class,
        ];

        yield [
            self::getTestResourceData('test.jpg'),
            [],
            ImageInterface::class,
        ];
    }

    public static function handleColorInputDataProvider(): Generator
    {
        yield [
            'ffffff',
            [],
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
