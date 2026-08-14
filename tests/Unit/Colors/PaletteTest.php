<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors;

use Intervention\Image\Color;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Cmyk\Colorspace as Cmyk;
use Intervention\Image\Colors\Hsl\Channels\Luminance;
use Intervention\Image\Colors\Palette;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

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
        $palette->addColor(Color::rgb(11, 11, 11));
        $palette->addColor(Color::rgb(255, 255, 255));
        $palette->addColor(Color::rgb(255, 255, 255));

        // unsorted
        $this->assertColor(0, 0, 0, 255, $palette->first());

        // low presence first
        $sorted = $palette->sortByPresence();
        $this->assertColor(11, 11, 11, 255, $sorted->first());
        $this->assertColor(11, 11, 11, 255, $palette->first());

        // hight presence first
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
}
