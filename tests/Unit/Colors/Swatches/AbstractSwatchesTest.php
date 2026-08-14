<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Swatches;

use Intervention\Image\Analyzers\QuantizedPaletteAnalyzer;
use Intervention\Image\Color;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Cmyk\Colorspace as Cmyk;
use Intervention\Image\Colors\Swatches\AbstractSwatches;
use Intervention\Image\Colors\Swatches\Filters\VibrantMutedFilter;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ColorFilterInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use Intervention\Image\Tests\BaseTestCase;

class AbstractSwatchesTest extends BaseTestCase
{
    public function testIteration(): void
    {
        $iterations = 0;
        foreach ($this->testImplementation() as $key => $color) {
            $iterations++;
            if ($key === 'vibrant' || $key === 'muted') {
                $this->assertInstanceOf(ColorInterface::class, $color);
            } else {
                $this->assertNull($color);
            }
        }

        $this->assertEquals(6, $iterations);
    }

    public function testToArray(): void
    {
        $swatchesArray = $this->testImplementation()->toArray();
        $this->assertIsArray($swatchesArray);
        $this->assertCount(6, $swatchesArray);
        foreach ($swatchesArray as $key => $color) {
            if ($key === 'vibrant' || $key === 'muted') {
                $this->assertInstanceOf(ColorInterface::class, $color);
            } else {
                $this->assertNull($color);
            }
        }
    }

    public function testCount(): void
    {
        $this->assertEquals(6, $this->testImplementation()->count());
    }

    public function testToColorspace(): void
    {
        $result = $this->testImplementation()->toColorspace(Cmyk::class);
        foreach ($result as $key => $color) {
            if ($key === 'vibrant' || $key === 'muted') {
                $this->assertInstanceOf(CmykColor::class, $color);
            } else {
                $this->assertNull($color);
            }
        }
    }

    public function testArrayAccess(): void
    {
        $swatches = $this->testImplementation();
        $this->assertColor(0, 0, 0, 255, $swatches['vibrant']);
        $this->assertColor(255, 255, 255, 255, $swatches['muted']);
        $this->assertNull($swatches['darkMuted']);
    }

    public function testOffsetUnset(): void
    {
        $swatches = $this->testImplementation();
        unset($swatches['vibrant']);
        $this->assertNull($swatches['vibrant']);

        // object must remain fully usable after unset
        $this->assertEquals(6, $swatches->count());
        $this->assertCount(6, $swatches->toArray());
    }

    public function testToPalette(): void
    {
        $palette = $this->testImplementation()->toPalette();
        $this->assertInstanceOf(PaletteInterface::class, $palette);
        $this->assertCount(2, $palette);
        $this->assertColor(0, 0, 0, 255, $palette[0]);
        $this->assertColor(255, 255, 255, 255, $palette[1]);
    }

    private function testImplementation(): AbstractSwatches
    {
        return new class () extends AbstractSwatches
        {
            public function __construct(
                public ?ColorInterface $vibrant = null,
                public ?ColorInterface $muted = null,
                public ?ColorInterface $darkVibrant = null,
                public ?ColorInterface $darkMuted = null,
                public ?ColorInterface $lightVibrant = null,
                public ?ColorInterface $lightMuted = null,
            ) {
                $this->vibrant = Color::rgb(0, 0, 0);
                $this->muted = Color::rgb(255, 255, 255);
            }

            public function colorAnalyzer(): AnalyzerInterface
            {
                return new QuantizedPaletteAnalyzer();
            }

            public function colorFilter(): ColorFilterInterface
            {
                return new VibrantMutedFilter();
            }
        };
    }
}
