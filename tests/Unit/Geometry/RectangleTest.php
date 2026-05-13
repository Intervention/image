<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry;

use Intervention\Image\Geometry\Factories\RectangleFactory;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Rectangle::class)]
final class RectangleTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $rectangle = new Rectangle(300, 200);
        $this->assertEquals(300, $rectangle->width());
        $this->assertEquals(200, $rectangle->height());
    }

    public function testFactory(): void
    {
        $rectangle = new Rectangle(300, 200);
        $factory = $rectangle->factory();
        $this->assertInstanceOf(RectangleFactory::class, $factory);
    }

    public function testAdjust(): void
    {
        $rectangle = new Rectangle(300, 200);
        $this->assertEquals(null, $rectangle->backgroundColor());
        $adjusted = $rectangle->adjust(fn(RectangleFactory $factory) => $factory->background('f50'));
        $this->assertEquals(null, $rectangle->backgroundColor());
        $this->assertEquals('f50', $adjusted->backgroundColor());
    }

    public function testSetWidth(): void
    {
        $rectangle = new Rectangle(300, 200);
        $this->assertEquals(300, $rectangle->width());
        $rectangle->setWidth(400);
        $this->assertEquals(400, $rectangle->width());
    }

    public function testSetHeight(): void
    {
        $rectangle = new Rectangle(300, 200);
        $this->assertEquals(200, $rectangle->height());
        $rectangle->setHeight(800);
        $this->assertEquals(800, $rectangle->height());
    }
}
