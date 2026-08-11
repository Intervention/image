<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors;

use Intervention\Image\Color;
use Intervention\Image\Colors\Classifier;
use Intervention\Image\Colors\RatedColor;
use Intervention\Image\Tests\BaseTestCase;

class ClassifierTest extends BaseTestCase
{
    public function testClassification(): void
    {
        $colors = [
            new RatedColor(Color::rgb(255, 0, 0), 1), // vibrant
            new RatedColor(Color::rgb(170, 100, 100), 1), // muted
            new RatedColor(Color::rgb(100, 0, 0), 1), // dark vibrant
            new RatedColor(Color::rgb(80, 45, 45), 1), // dark muted
            new RatedColor(Color::rgb(255, 200, 200), 1), // light vibrant
            new RatedColor(Color::rgb(180, 170, 160), 1), // light muted
        ];

        $classifier = new Classifier($colors);

        $this->assertColor(255, 0, 0, 255, $classifier->vibrant());
        $this->assertColor(170, 100, 100, 255, $classifier->muted());
        $this->assertColor(100, 0, 0, 255, $classifier->darkVibrant());
        $this->assertColor(80, 45, 45, 255, $classifier->darkMuted());
        $this->assertColor(255, 200, 200, 255, $classifier->lightVibrant());
        $this->assertColor(180, 170, 160, 255, $classifier->lightMuted());
    }

    public function testClassificationNotFound(): void
    {
        $colors = [
            new RatedColor(Color::rgb(255, 0, 0), 1), // vibrant
            new RatedColor(Color::rgb(170, 100, 100), 1), // muted
            new RatedColor(Color::rgb(100, 0, 0), 1), // dark vibrant
            new RatedColor(Color::rgb(80, 45, 45), 1), // dark muted
        ];

        $classifier = new Classifier($colors);

        $this->assertColor(255, 0, 0, 255, $classifier->vibrant());
        $this->assertColor(170, 100, 100, 255, $classifier->muted());
        $this->assertColor(100, 0, 0, 255, $classifier->darkVibrant());
        $this->assertColor(80, 45, 45, 255, $classifier->darkMuted());
        $this->assertNull($classifier->lightVibrant());
        $this->assertNull($classifier->lightMuted());
    }
}
