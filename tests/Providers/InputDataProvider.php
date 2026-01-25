<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Providers;

use Generator;
use Intervention\Image\Alignment;
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

    public static function alignmentInputs(): Generator
    {
        yield [Alignment::TOP, Alignment::TOP];
        yield ['top', Alignment::TOP];
        yield ['top-center', Alignment::TOP];
        yield ['center-top', Alignment::TOP];
        yield ['top-middle', Alignment::TOP];
        yield ['middle-top', Alignment::TOP];
        yield ['top-right', Alignment::TOP_RIGHT];
        yield ['right-top', Alignment::TOP_RIGHT];

        yield [Alignment::RIGHT, Alignment::RIGHT];
        yield ['right', Alignment::RIGHT];
        yield ['right-center', Alignment::RIGHT];
        yield ['center-right', Alignment::RIGHT];
        yield ['right-middle', Alignment::RIGHT];
        yield ['middle-right', Alignment::RIGHT];

        yield [Alignment::BOTTOM_RIGHT, Alignment::BOTTOM_RIGHT];
        yield ['right-bottom', Alignment::BOTTOM_RIGHT];
        yield ['bottom-right', Alignment::BOTTOM_RIGHT];

        yield [Alignment::BOTTOM, Alignment::BOTTOM];
        yield ['bottom', Alignment::BOTTOM];
        yield ['bottom-center', Alignment::BOTTOM];
        yield ['center-bottom', Alignment::BOTTOM];
        yield ['bottom-middle', Alignment::BOTTOM];
        yield ['middle-bottom', Alignment::BOTTOM];

        yield [Alignment::BOTTOM_LEFT, Alignment::BOTTOM_LEFT];
        yield ['left-bottom', Alignment::BOTTOM_LEFT];
        yield ['bottom-left', Alignment::BOTTOM_LEFT];

        yield [Alignment::LEFT, Alignment::LEFT];
        yield ['left', Alignment::LEFT];
        yield ['left-center', Alignment::LEFT];
        yield ['center-left', Alignment::LEFT];
        yield ['left-middle', Alignment::LEFT];
        yield ['middle-left', Alignment::LEFT];

        yield [Alignment::TOP_LEFT, Alignment::TOP_LEFT];
        yield ['left-top', Alignment::TOP_LEFT];
        yield ['top-left', Alignment::TOP_LEFT];

        yield [Alignment::CENTER, Alignment::CENTER];
        yield ['center', Alignment::CENTER];
        yield ['middle', Alignment::CENTER];
        yield ['center-center', Alignment::CENTER];
        yield ['center-middle', Alignment::CENTER];
        yield ['middle-center', Alignment::CENTER];
    }
}
