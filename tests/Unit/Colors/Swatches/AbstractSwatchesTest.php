<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Swatches;

use Intervention\Image\Color;
use Intervention\Image\Colors\Swatches\AbstractSwatches;
use Intervention\Image\Colors\Swatches\Filters\VibrantMutedFilter;
use Intervention\Image\Interfaces\ColorFilterInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use Intervention\Image\Tests\BaseTestCase;

class AbstractSwatchesTest extends BaseTestCase
{
    public function testToPalette(): void
    {
        $result = $this->testImplementation()->toPalette();
        $this->assertInstanceOf(PaletteInterface::class, $result);
    }

    public function testIteration(): void
    {
        $iterations = 0;
        foreach ($this->testImplementation() as $color) {
            $iterations++;
            $this->assertInstanceOf(ColorInterface::class, $color);
        }

        $this->assertEquals(2, $iterations);
    }

    public function testToArray(): void
    {
        $this->assertIsArray($this->testImplementation()->toArray());
    }

    public function testToCount(): void
    {
        $this->assertEquals(2, $this->testImplementation()->count());
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

            public function colorFilter(): ColorFilterInterface
            {
                return new VibrantMutedFilter();
            }
        };
    }
}
