<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Colors\Hsl\Channels;

use Intervention\Image\Colors\Hsl\Channels\Saturation;
use Intervention\Image\Tests\TestCase;

final class SaturationTest extends TestCase
{
    public function testMinMax(): void
    {
        $saturation = new Saturation(0);
        $this->assertEquals(0, $saturation->min());
        $this->assertEquals(100, $saturation->max());
    }
}
