<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry;

use Intervention\Image\Colors\Rgb\Color;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Geometry\Pixel;
use Intervention\Image\Tests\BaseTestCase;

#[CoversClass(Pixel::class)]
final class PixelTest extends BaseTestCase
{
    public function testSetGetBackground(): void
    {
        $color = new Color(255, 55, 0);
        $pixel = new Pixel(new Color(0, 0, 0), 10, 12);
        $result = $pixel->setBackgroundColor($color);
        $this->assertEquals($color, $pixel->backgroundColor());
        $this->assertInstanceOf(Pixel::class, $result);
    }
}
