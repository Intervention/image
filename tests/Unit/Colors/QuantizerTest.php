<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors;

use Generator;
use Intervention\Image\Color;
use Intervention\Image\Colors\Quantizer;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class QuantizerTest extends BaseTestCase
{
    public function testLevelTooLow(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Quantizer(0);
    }

    public function testLevelTooHigh(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Quantizer(1000);
    }

    #[DataProvider('rgb')]
    #[DataProvider('cmyk')]
    public function testQuantizeColor(int $level, ColorInterface $input, ColorInterface $output): void
    {
        $this->assertEquals($output, (new Quantizer($level))->quantizeColor($input));
    }

    public function testQuantizeColorsKeepsActualColors(): void
    {
        // the palette must contain the first actual color of each bin
        // instead of the calculated bin center
        $palette = (new Quantizer(16))->quantizeColors([
            Color::rgb(100, 100, 100),
            Color::rgb(101, 101, 101), // same bin as previous color
        ]);

        $this->assertCount(1, $palette);
        $this->assertColor(100, 100, 100, 255, $palette->first());
        $this->assertEquals(2, $palette->totalCount());
    }

    public function testQuantizeColorsGroupsColorsIgnoringAlpha(): void
    {
        // colors that only differ in alpha must share one bin
        $palette = (new Quantizer(16))->quantizeColors([
            Color::rgb(100, 100, 100),
            Color::rgb(100, 100, 100)->withTransparency(0.5),
        ]);

        $this->assertCount(1, $palette);
        $this->assertColor(100, 100, 100, 255, $palette->first());
        $this->assertEquals(2, $palette->totalCount());
    }

    public static function rgb(): Generator
    {
        yield [8, Color::rgb(255, 0, 0), Color::rgb(239, 16, 16)];
        yield [16, Color::rgb(255, 0, 0), Color::rgb(247, 8, 8)];
        yield [32, Color::rgb(255, 0, 0), Color::rgb(251, 4, 4)];
        yield [64, Color::rgb(255, 0, 0), Color::rgb(253, 2, 2)];
        yield [128, Color::rgb(255, 0, 0), Color::rgb(254, 1, 1)];
        yield [256, Color::rgb(255, 0, 0), Color::rgb(255, 0, 0)];
    }

    public static function cmyk(): Generator
    {
        yield [8, Color::cmyk(100, 50, 100, 0), Color::cmyk(94, 56, 94, 6)];
    }
}
