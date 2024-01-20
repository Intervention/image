<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Geometry;

use Intervention\Image\Geometry\Pixel;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Tests\TestCase;
use Mockery;

/**
 * @covers \Intervention\Image\Geometry\Pixel
 */
class PixelTest extends TestCase
{
    public function testSetGetBackground(): void
    {
        $color = Mockery::mock(ColorInterface::class);
        $pixel = new Pixel($color, 10, 12);
        $result = $pixel->setBackgroundColor($color);
        $this->assertInstanceOf(ColorInterface::class, $pixel->backgroundColor());
        $this->assertInstanceOf(Pixel::class, $result);
    }
}
