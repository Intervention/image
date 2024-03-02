<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Geometry\Pixel;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;

#[CoversClass(\Intervention\Image\Geometry\Pixel::class)]
final class PixelTest extends BaseTestCase
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
