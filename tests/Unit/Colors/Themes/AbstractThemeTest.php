<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Themes;

use Intervention\Image\Color;
use Intervention\Image\Colors\Themes\AbstractTheme;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use Intervention\Image\Tests\BaseTestCase;

class AbstractThemeTest extends BaseTestCase
{
    public function testToPalette(): void
    {
        $palette = $this->testImplementation()->toPalette();
        $this->assertInstanceOf(PaletteInterface::class, $palette);
        $this->assertCount(2, $palette);
        $this->assertColor(0, 0, 0, 255, $palette[0]);
        $this->assertColor(255, 255, 255, 255, $palette[1]);
    }

    private function testImplementation(): AbstractTheme
    {
        return new class () extends AbstractTheme
        {
            public function __construct(
                public ?ColorInterface $test1 = null,
                public ?ColorInterface $test2 = null,
                public ?ColorInterface $test3 = null,
            ) {
                $this->test1 = Color::rgb(0, 0, 0);
                $this->test2 = Color::rgb(255, 255, 255);
            }
        };
    }
}
