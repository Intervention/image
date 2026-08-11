<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors;

use Intervention\Image\Color;
use Intervention\Image\Colors\RatedColor;
use Intervention\Image\Tests\BaseTestCase;

class RatedColorTest extends BaseTestCase
{
    protected RatedColor $color;

    public function setUp(): void
    {
        $this->color = new RatedColor(Color::rgb(255, 0, 0), 12);
    }

    public function testHash(): void
    {
        $this->assertEquals('5e2287cf91a59aefced3e980024498f7', $this->color->hash());
    }

    public function testIncreaseRating(): void
    {
        $this->assertEquals(12, $this->color->rating);
        $this->color->increaseRating();
        $this->color->increaseRating();
        $this->color->increaseRating();
        $this->assertEquals(15, $this->color->rating);
    }
}
