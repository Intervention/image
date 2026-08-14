<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Swatches\Filters;

use Intervention\Image\Color;
use Intervention\Image\Colors\Palette;
use Intervention\Image\Colors\Swatches\Filters\VibrantMutedFilter;
use Intervention\Image\Tests\BaseTestCase;

class VibrantMutedFilterTest extends BaseTestCase
{
    public function testFilterColors(): void
    {
        $palette = new Palette([
            Color::rgb(255, 0, 0), // vibrant
            Color::rgb(170, 100, 100), // muted
            Color::rgb(100, 0, 0), // dark vibrant
            Color::rgb(80, 45, 45), // dark muted
            Color::rgb(255, 200, 200), // light vibrant
            Color::rgb(180, 170, 160), // light muted
        ]);

        $filter = new VibrantMutedFilter();
        $result = $filter->filterColors($palette);

        $this->assertColor(255, 0, 0, 255, $result->vibrant);
        $this->assertColor(170, 100, 100, 255, $result->muted);
        $this->assertColor(100, 0, 0, 255, $result->darkVibrant);
        $this->assertColor(80, 45, 45, 255, $result->darkMuted);
        $this->assertColor(255, 200, 200, 255, $result->lightVibrant);
        $this->assertColor(180, 170, 160, 255, $result->lightMuted);
    }

    public function testFilterColorsNotFound(): void
    {
        $palette = new Palette([
            Color::rgb(255, 0, 0), // vibrant
            Color::rgb(170, 100, 100), // muted
            Color::rgb(100, 0, 0), // dark vibrant
            Color::rgb(80, 45, 45), // dark muted
            Color::rgb(0, 0, 0),
            Color::rgb(0, 1, 0),
        ]);

        $filter = new VibrantMutedFilter();
        $result = $filter->filterColors($palette);

        $this->assertColor(255, 0, 0, 255, $result->vibrant);
        $this->assertColor(170, 100, 100, 255, $result->muted);
        $this->assertColor(100, 0, 0, 255, $result->darkVibrant);
        $this->assertColor(80, 45, 45, 255, $result->darkMuted);
        $this->assertNull($result->lightVibrant);
        $this->assertNull($result->lightMuted);
    }
}
