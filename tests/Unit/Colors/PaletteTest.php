<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors;

use Generator;
use Intervention\Image\Color;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Cmyk\Colorspace as Cmyk;
use Intervention\Image\Colors\Hsl\Channels\Luminance;
use Intervention\Image\Colors\Palette;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(Palette::class)]
class PaletteTest extends BaseTestCase
{
    protected Palette $palette;

    protected function setUp(): void
    {
        $this->palette = new Palette([
            Color::rgb(255, 0, 0),
            Color::rgb(0, 255, 0),
            Color::rgb(0, 0, 255),
        ]);
    }

    public function testCountableIteration(): void
    {
        $this->assertCount(3, $this->palette);
        $this->assertCount(3, $this->palette->toArray());

        foreach ($this->palette as $color) {
            $this->assertInstanceOf(ColorInterface::class, $color);
        }

        foreach ($this->palette as $color) {
            $this->assertInstanceOf(ColorInterface::class, $color);
        }
    }

    public function testFirstLast(): void
    {
        $this->assertInstanceOf(ColorInterface::class, $this->palette->first());
        $this->assertInstanceOf(ColorInterface::class, $this->palette->last());
    }

    public function testToColorspace(): void
    {
        $result = $this->palette->toColorspace(Cmyk::class);
        $this->assertInstanceOf(PaletteInterface::class, $result);
        foreach ($result as $color) {
            $this->assertInstanceOf(CmykColor::class, $color);
        }

        foreach ($this->palette as $color) {
            $this->assertInstanceOf(CmykColor::class, $color);
        }
    }

    public function testToColorspaceRehashesBins(): void
    {
        // color lookups must work with the converted colors
        $palette = new Palette([Color::rgb(255, 0, 0)]);
        $palette->toColorspace(Cmyk::class);
        $this->assertEquals(1, $palette->colorCount(Color::rgb(255, 0, 0)->toColorspace(Cmyk::class)));
    }

    public function testToColorspaceMergesIdenticalColors(): void
    {
        // both colors convert to cmyk(0 100 100 0) and must end up in one bin
        $palette = new Palette([Color::rgb(255, 0, 0), Color::rgb(254, 0, 0)]);
        $palette->toColorspace(Cmyk::class);
        $this->assertCount(1, $palette);
        $this->assertEquals(2, $palette->totalCount());
    }

    public function testOffsetSetNotSupported(): void
    {
        $this->expectException(NotSupportedException::class);
        $this->palette[0] = Color::rgb(1, 2, 3);
    }

    public function testOffsetUnsetNotSupported(): void
    {
        $this->expectException(NotSupportedException::class);
        unset($this->palette[0]);
    }

    public function testSortByChannel(): void
    {
        $result = $this->palette->sortByChannel(Red::class);
        $this->assertColor(255, 0, 0, 255, $result->last());

        $result = $this->palette->sortByChannel(Green::class);
        $this->assertColor(0, 255, 0, 255, $result->last());

        $result = $this->palette->sortByChannel(Blue::class);
        $this->assertColor(0, 0, 255, 255, $result->last());

        $result = $this->palette->sortByChannelDesc(Red::class);
        $this->assertColor(255, 0, 0, 255, $result->first());

        $result = $this->palette->sortByChannelDesc(Green::class);
        $this->assertColor(0, 255, 0, 255, $result->first());

        $result = $this->palette->sortByChannelDesc(Blue::class);
        $this->assertColor(0, 0, 255, 255, $result->first());
    }

    public function testSortByChannelMixedColorspaces(): void
    {
        $palette = new Palette([
            Color::rgb(255, 0, 0),
            Color::cmyk(100, 0, 0, 0), // rgb(0 255 255), no red at all
        ]);

        $result = $palette->sortByChannel(Red::class);
        $this->assertInstanceOf(CmykColor::class, $result->first());
        $this->assertColor(255, 0, 0, 255, $result->last());
    }

    public function testSortingKeepsChannelValues(): void
    {
        $palette = new Palette([
            Color::rgb(55, 55, 55),
            Color::rgb(100, 150, 200),
            Color::rgb(10, 20, 30),
            Color::rgb(55, 55, 55),
        ]);

        $result = $palette->sortByChannelDesc(Luminance::class);
        $this->assertColor(100, 150, 200, 255, $result->first());
        $this->assertColor(10, 20, 30, 255, $result->last());
    }

    public function testSlice(): void
    {
        $result = $this->palette->slice(1, 2);
        $this->assertCount(2, $result);
        $this->assertColor(0, 255, 0, 255, $result->first());
        $this->assertColor(0, 0, 255, 255, $result->last());
    }

    public function testAddColor(): void
    {
        $palette = new Palette();
        $this->assertEquals(0, $palette->totalCount());
        $palette->addColor(Color::rgb(0, 0, 0));
        $this->assertEquals(1, $palette->totalCount());
        $palette->addColor(Color::rgb(0, 0, 0));
        $this->assertEquals(2, $palette->totalCount());
        $palette->addColor(Color::rgb(0, 0, 0), 3);
        $this->assertEquals(5, $palette->totalCount());
    }

    public function testTotalCount(): void
    {
        $palette = new Palette([
            Color::rgb(255, 0, 0),
            Color::rgb(255, 0, 0),
            Color::rgb(255, 0, 0),
            Color::rgb(255, 0, 0),
            Color::rgb(255, 0, 0),
            Color::rgb(170, 100, 100),
            Color::rgb(170, 100, 100),
            Color::rgb(170, 100, 100),
            Color::rgb(170, 100, 100),
            Color::rgb(100, 0, 0),
            Color::rgb(100, 0, 0),
            Color::rgb(100, 0, 0),
            Color::rgb(80, 45, 45),
            Color::rgb(80, 45, 45),
            Color::rgb(255, 200, 200),
        ]);

        $this->assertEquals(15, $palette->totalCount());
    }

    public function testSortByPresence(): void
    {
        $palette = new Palette([]);
        $palette->addColor(Color::rgb(0, 0, 0));
        $palette->addColor(Color::rgb(0, 0, 0));
        $palette->addColor(Color::rgb(255, 255, 255));
        $palette->addColor(Color::rgb(0, 1, 2));
        $palette->addColor(Color::rgb(1, 2, 3));
        $palette->addColor(Color::rgb(255, 255, 255));
        $palette->addColor(Color::rgb(255, 255, 255));

        // unsorted
        $this->assertColor(0, 0, 0, 255, $palette->first());

        // low presence first, there are two colors with low presence (1)
        // but 1, 2, 3 comes first because of secondary hash sorting
        $sorted = $palette->sortByPresence();
        $this->assertColor(1, 2, 3, 255, $sorted->first());
        $this->assertColor(1, 2, 3, 255, $palette->first());

        // high presence first
        $sorted = $palette->sortByPresenceDesc();
        $this->assertColor(255, 255, 255, 255, $sorted->first());
        $this->assertColor(255, 255, 255, 255, $palette->first());
    }

    public function testColorCount(): void
    {
        $palette = new Palette([
            Color::rgb(100, 0, 0),
            Color::rgb(100, 0, 0),
            Color::rgb(100, 0, 0),
            Color::rgb(0, 100, 0),
            Color::rgb(0, 100, 0),
            Color::rgb(0, 0, 100),
        ]);

        $this->assertEquals(3, $palette->colorCount(Color::rgb(100, 0, 0)));
        $this->assertEquals(2, $palette->colorCount(Color::rgb(0, 100, 0)));
        $this->assertEquals(1, $palette->colorCount(Color::rgb(0, 0, 100)));
        $this->assertEquals(0, $palette->colorCount(Color::rgb(0, 0, 0)));
    }

    public function testHasColor(): void
    {
        $palette = new Palette();
        $this->assertFalse($palette->hasColor(Color::rgb(0, 0, 0)));
        $this->assertFalse($palette->hasColor(Color::rgb(255, 255, 255)));
        $this->assertFalse($palette->hasColor(Color::rgb(1, 1, 1)));

        $palette->addColor(Color::rgb(0, 0, 0));
        $this->assertTrue($palette->hasColor(Color::rgb(0, 0, 0)));
        $this->assertFalse($palette->hasColor(Color::rgb(255, 255, 255)));
        $this->assertFalse($palette->hasColor(Color::rgb(1, 1, 1)));

        $palette->addColor(Color::rgb(255, 255, 255));
        $this->assertTrue($palette->hasColor(Color::rgb(0, 0, 0)));
        $this->assertTrue($palette->hasColor(Color::rgb(255, 255, 255)));
        $this->assertFalse($palette->hasColor(Color::rgb(1, 1, 1)));
    }

    /**
     * @param array<ColorInterface> $colors
     */
    #[DataProvider('quantizePaletteProvider')]
    public function testQuantize(
        array $colors,
        int $levels,
        int $totalCount,
        int $countBeforeQuantization,
        int $countAfterQuantization,
        ColorInterface $firstColorAfterQuantization,
        ColorInterface $lastColorAfterQuantization,
    ): void {
        $palette = new Palette($colors);
        $this->assertEquals($countBeforeQuantization, $palette->count());
        $this->assertEquals($totalCount, $palette->totalCount());

        $quantizedPalette = $palette->quantize($levels);

        $this->assertEquals($countAfterQuantization, $quantizedPalette->count());
        $this->assertEquals($totalCount, $quantizedPalette->totalCount());
        $this->assertEquals($firstColorAfterQuantization, $quantizedPalette->first());
        $this->assertEquals($lastColorAfterQuantization, $quantizedPalette->last());
        $this->assertEquals($countAfterQuantization, $palette->count());
        $this->assertEquals($totalCount, $palette->totalCount());
        $this->assertEquals($firstColorAfterQuantization, $palette->first());
        $this->assertEquals($lastColorAfterQuantization, $palette->last());
    }

    public static function quantizePaletteProvider(): Generator
    {
        $colors = [];
        for ($i = 0; $i < 256; $i++) {
            $colors[] = Color::rgb($i, $i, $i);
        }

        yield [$colors, 1, 256, 256, 1, Color::rgb(128, 128, 128), Color::rgb(128, 128, 128)];
        yield [$colors, 2, 256, 256, 2, Color::rgb(64, 64, 64), Color::rgb(191, 191, 191)];
        yield [$colors, 4, 256, 256, 4, Color::rgb(32, 32, 32), Color::rgb(223, 223, 223)];
        yield [$colors, 6, 256, 256, 6, Color::rgb(21, 21, 21), Color::rgb(234, 234, 234)];
        yield [$colors, 8, 256, 256, 8, Color::rgb(16, 16, 16), Color::rgb(239, 239, 239)];
        yield [$colors, 10, 256, 256, 10, Color::rgb(13, 13, 13), Color::rgb(242, 242, 242)];
        yield [$colors, 12, 256, 256, 12, Color::rgb(11, 11, 11), Color::rgb(244, 244, 244)];
        yield [$colors, 14, 256, 256, 14, Color::rgb(9, 9, 9), Color::rgb(246, 246, 246)];
        yield [$colors, 16, 256, 256, 16, Color::rgb(8, 8, 8), Color::rgb(247, 247, 247)];
    }

    /**
     * @param array<ColorInterface> $colors
     */
    #[DataProvider('reducePaletteProvider')]
    public function testReduce(
        array $colors,
        int $levels,
        int $totalCount,
        int $countBeforeQuantization,
        int $countAfterQuantization,
        ColorInterface $firstColorAfterQuantization,
        ColorInterface $lastColorAfterQuantization,
    ): void {
        $palette = new Palette($colors);
        $this->assertEquals($countBeforeQuantization, $palette->count());
        $this->assertEquals($totalCount, $palette->totalCount());
        $preserveThisColor = $palette->first();

        $quantizedPalette = $palette->reduce($levels);

        $this->assertEquals($countAfterQuantization, $quantizedPalette->count());
        $this->assertEquals($totalCount, $quantizedPalette->totalCount());
        $this->assertEquals($firstColorAfterQuantization, $quantizedPalette->first());
        $this->assertEquals($lastColorAfterQuantization, $quantizedPalette->last());
        $this->assertEquals($countAfterQuantization, $palette->count());
        $this->assertEquals($totalCount, $palette->totalCount());
        $this->assertEquals($firstColorAfterQuantization, $palette->first());
        $this->assertEquals($lastColorAfterQuantization, $palette->last());
        $this->assertTrue($quantizedPalette->hasColor($preserveThisColor));
        $this->assertTrue($palette->hasColor($preserveThisColor));
    }

    public static function reducePaletteProvider(): Generator
    {
        $colors = [];
        for ($i = 0; $i < 256; $i++) {
            $colors[] = Color::rgb($i, $i, $i);
        }

        yield [$colors, 1, 256, 256, 1, Color::rgb(0, 0, 0), Color::rgb(0, 0, 0)];
        yield [$colors, 2, 256, 256, 2, Color::rgb(0, 0, 0), Color::rgb(128, 128, 128)];
        yield [$colors, 4, 256, 256, 4, Color::rgb(0, 0, 0), Color::rgb(192, 192, 192)];
        yield [$colors, 6, 256, 256, 6, Color::rgb(0, 0, 0), Color::rgb(213, 213, 213)];
        yield [$colors, 8, 256, 256, 8, Color::rgb(0, 0, 0), Color::rgb(224, 224, 224)];
        yield [$colors, 10, 256, 256, 10, Color::rgb(0, 0, 0), Color::rgb(230, 230, 230)];
        yield [$colors, 12, 256, 256, 12, Color::rgb(0, 0, 0), Color::rgb(234, 234, 234)];
        yield [$colors, 14, 256, 256, 14, Color::rgb(0, 0, 0), Color::rgb(237, 237, 237)];
        yield [$colors, 16, 256, 256, 16, Color::rgb(0, 0, 0), Color::rgb(240, 240, 240)];
    }
}
