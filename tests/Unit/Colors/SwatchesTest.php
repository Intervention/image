<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors;

use Intervention\Image\Color;
use Intervention\Image\Colors\RatedColor;
use Intervention\Image\Colors\Swatches;
use Intervention\Image\Tests\BaseTestCase;

class SwatchesTest extends BaseTestCase
{
    public function testSwatches(): void
    {
        $colors = [
            new RatedColor(Color::rgb(255, 0, 0), 1), // vibrant
            new RatedColor(Color::rgb(170, 100, 100), 1), // muted
            new RatedColor(Color::rgb(100, 0, 0), 1), // dark vibrant
            new RatedColor(Color::rgb(80, 45, 45), 1), // dark muted
            new RatedColor(Color::rgb(255, 200, 200), 1), // light vibrant
            new RatedColor(Color::rgb(180, 170, 160), 1), // light muted
        ];

        $swatches = new Swatches($colors);

        $this->assertColor(255, 0, 0, 255, $swatches->vibrant());
        $this->assertColor(170, 100, 100, 255, $swatches->muted());
        $this->assertColor(100, 0, 0, 255, $swatches->darkVibrant());
        $this->assertColor(80, 45, 45, 255, $swatches->darkMuted());
        $this->assertColor(255, 200, 200, 255, $swatches->lightVibrant());
        $this->assertColor(180, 170, 160, 255, $swatches->lightMuted());
    }
}
