<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Colors\Hsv\Channels;

use Intervention\Image\Colors\Hsv\Channels\Value;
use Intervention\Image\Tests\TestCase;

class ValueTest extends TestCase
{
    public function testMinMax(): void
    {
        $saturation = new Value(0);
        $this->assertEquals(0, $saturation->min());
        $this->assertEquals(100, $saturation->max());
    }
}
