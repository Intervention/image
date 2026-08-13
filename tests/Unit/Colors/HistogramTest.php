<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors;

use Intervention\Image\Color;
use Intervention\Image\Colors\Bin;
use Intervention\Image\Colors\Histogram;
use Intervention\Image\Interfaces\PaletteInterface;
use Intervention\Image\Tests\BaseTestCase;

class HistogramTest extends BaseTestCase
{
    public function testHistogram(): void
    {
        $histogram = new Histogram([
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

        $this->assertEquals(15, $histogram->totalCount());

        foreach ($histogram as $color) {
            $this->assertInstanceOf(Bin::class, $color);
        }
    }

    public function testAddColor(): void
    {
        $histogram = new Histogram();
        $this->assertEquals(0, $histogram->totalCount());
        $histogram->addColor(Color::rgb(0, 0, 0));
        $this->assertEquals(1, $histogram->totalCount());
        $histogram->addColor(Color::rgb(0, 0, 0));
        $this->assertEquals(2, $histogram->totalCount());
        $histogram->addColor(Color::rgb(0, 0, 0), 3);
        $this->assertEquals(5, $histogram->totalCount());
    }

    public function testToPalette(): void
    {
        $histogram = new Histogram();
        $this->assertInstanceOf(PaletteInterface::class, $histogram->toPalette());
    }
}
