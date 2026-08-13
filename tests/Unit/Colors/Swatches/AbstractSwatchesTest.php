<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Swatches;

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
            $this->assertNull($color);
        }

        $this->assertEquals(6, $iterations);
    }

    public function testToArray(): void
    {
        $this->assertIsArray($this->testImplementation()->toArray());
    }

    public function testToCount(): void
    {
        $this->assertEquals(6, $this->testImplementation()->count());
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
                //
            }

            public function colorFilter(): ColorFilterInterface
            {
                return new VibrantMutedFilter();
            }
        };
    }
}
