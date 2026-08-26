<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Themes\VibrantMuted;

use Intervention\Image\Color;
use Intervention\Image\Colors\Themes\VibrantMuted\Theme;
use Intervention\Image\Interfaces\PaletteInterface;
use Intervention\Image\Tests\BaseTestCase;

class ThemeTest extends BaseTestCase
{
    public function testToPalette(): void
    {
        $palette = (new Theme(
            vibrant: Color::rgb(1, 0, 0),
            muted: Color::rgb(2, 0, 0),
            lightVibrant: Color::rgb(3, 0, 0),
            lightMuted: Color::rgb(4, 0, 0),
        ))->toPalette();

        $this->assertInstanceOf(PaletteInterface::class, $palette);
        $this->assertCount(4, $palette);
        $this->assertColor(1, 0, 0, 255, $palette[0]);
        $this->assertColor(2, 0, 0, 255, $palette[1]);
        $this->assertColor(3, 0, 0, 255, $palette[2]);
        $this->assertColor(4, 0, 0, 255, $palette[3]);
    }
}
