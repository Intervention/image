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
    public static function decodeImageDataProvider(): Generator
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

    public static function decodeColorDataProvider(): Generator
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
        yield ['top_center', Alignment::TOP];
        yield ['topcenter', Alignment::TOP];
        yield ['center-top', Alignment::TOP];
        yield ['center_top', Alignment::TOP];
        yield ['centertop', Alignment::TOP];
        yield ['top-middle', Alignment::TOP];
        yield ['top_middle', Alignment::TOP];
        yield ['topmiddle', Alignment::TOP];
        yield ['middle-top', Alignment::TOP];
        yield ['middle_top', Alignment::TOP];
        yield ['middletop', Alignment::TOP];
        yield ['top-right', Alignment::TOP_RIGHT];
        yield ['top_right', Alignment::TOP_RIGHT];
        yield ['topright', Alignment::TOP_RIGHT];
        yield ['right-top', Alignment::TOP_RIGHT];
        yield ['right_top', Alignment::TOP_RIGHT];
        yield ['righttop', Alignment::TOP_RIGHT];

        yield [Alignment::RIGHT, Alignment::RIGHT];
        yield ['right', Alignment::RIGHT];
        yield ['right-center', Alignment::RIGHT];
        yield ['right_center', Alignment::RIGHT];
        yield ['rightcenter', Alignment::RIGHT];
        yield ['center-right', Alignment::RIGHT];
        yield ['center_right', Alignment::RIGHT];
        yield ['centerright', Alignment::RIGHT];
        yield ['right-middle', Alignment::RIGHT];
        yield ['right_middle', Alignment::RIGHT];
        yield ['rightmiddle', Alignment::RIGHT];
        yield ['middle-right', Alignment::RIGHT];
        yield ['middle_right', Alignment::RIGHT];
        yield ['middleright', Alignment::RIGHT];

        yield [Alignment::BOTTOM_RIGHT, Alignment::BOTTOM_RIGHT];
        yield ['right-bottom', Alignment::BOTTOM_RIGHT];
        yield ['right_bottom', Alignment::BOTTOM_RIGHT];
        yield ['rightbottom', Alignment::BOTTOM_RIGHT];
        yield ['bottom-right', Alignment::BOTTOM_RIGHT];
        yield ['bottom_right', Alignment::BOTTOM_RIGHT];
        yield ['bottomright', Alignment::BOTTOM_RIGHT];

        yield [Alignment::BOTTOM, Alignment::BOTTOM];
        yield ['bottom', Alignment::BOTTOM];
        yield ['bottom-center', Alignment::BOTTOM];
        yield ['bottom_center', Alignment::BOTTOM];
        yield ['bottomcenter', Alignment::BOTTOM];
        yield ['center-bottom', Alignment::BOTTOM];
        yield ['center_bottom', Alignment::BOTTOM];
        yield ['centerbottom', Alignment::BOTTOM];
        yield ['bottom-middle', Alignment::BOTTOM];
        yield ['bottom_middle', Alignment::BOTTOM];
        yield ['bottommiddle', Alignment::BOTTOM];
        yield ['middle-bottom', Alignment::BOTTOM];
        yield ['middle_bottom', Alignment::BOTTOM];
        yield ['middlebottom', Alignment::BOTTOM];

        yield [Alignment::BOTTOM_LEFT, Alignment::BOTTOM_LEFT];
        yield ['left-bottom', Alignment::BOTTOM_LEFT];
        yield ['left_bottom', Alignment::BOTTOM_LEFT];
        yield ['leftbottom', Alignment::BOTTOM_LEFT];
        yield ['bottom-left', Alignment::BOTTOM_LEFT];
        yield ['bottom_left', Alignment::BOTTOM_LEFT];
        yield ['bottomleft', Alignment::BOTTOM_LEFT];

        yield [Alignment::LEFT, Alignment::LEFT];
        yield ['left', Alignment::LEFT];
        yield ['left-center', Alignment::LEFT];
        yield ['left_center', Alignment::LEFT];
        yield ['leftcenter', Alignment::LEFT];
        yield ['center-left', Alignment::LEFT];
        yield ['center_left', Alignment::LEFT];
        yield ['centerleft', Alignment::LEFT];
        yield ['left-middle', Alignment::LEFT];
        yield ['left_middle', Alignment::LEFT];
        yield ['leftmiddle', Alignment::LEFT];
        yield ['middle-left', Alignment::LEFT];
        yield ['middle_left', Alignment::LEFT];
        yield ['middleleft', Alignment::LEFT];

        yield [Alignment::TOP_LEFT, Alignment::TOP_LEFT];
        yield ['left-top', Alignment::TOP_LEFT];
        yield ['left_top', Alignment::TOP_LEFT];
        yield ['lefttop', Alignment::TOP_LEFT];
        yield ['top-left', Alignment::TOP_LEFT];
        yield ['top_left', Alignment::TOP_LEFT];
        yield ['topleft', Alignment::TOP_LEFT];

        yield [Alignment::CENTER, Alignment::CENTER];
        yield ['center', Alignment::CENTER];
        yield ['middle', Alignment::CENTER];
        yield ['center-center', Alignment::CENTER];
        yield ['center_center', Alignment::CENTER];
        yield ['centercenter', Alignment::CENTER];
        yield ['center-middle', Alignment::CENTER];
        yield ['center_middle', Alignment::CENTER];
        yield ['centermiddle', Alignment::CENTER];
        yield ['middle-center', Alignment::CENTER];
        yield ['middle_center', Alignment::CENTER];
        yield ['middlecenter', Alignment::CENTER];
    }
}
