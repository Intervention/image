<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors;

use Intervention\Image\Color;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Cmyk\Colorspace as Cmyk;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Palette;
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

        foreach ($this->palette->colors as $color) {
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
            $this->assertInstanceOf(RgbColor::class, $color);
        }
    }
}
