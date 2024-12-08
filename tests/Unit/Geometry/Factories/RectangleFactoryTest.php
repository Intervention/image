<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry\Factories;

use Intervention\Image\Geometry\Factories\RectangleFactory;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(RectangleFactory::class)]
final class RectangleFactoryTest extends BaseTestCase
{
    public function testFactoryCallback(): void
    {
        $factory = new RectangleFactory(new Point(1, 2), function ($rectangle) {
            $rectangle->background('fff');
            $rectangle->border('ccc', 10);
            $rectangle->width(100);
            $rectangle->height(200);
            $rectangle->size(1000, 2000);
        });

        $rectangle = $factory();
        $this->assertInstanceOf(Rectangle::class, $rectangle);
        $this->assertTrue($rectangle->hasBackgroundColor());
        $this->assertEquals('fff', $rectangle->backgroundColor());
        $this->assertEquals('ccc', $rectangle->borderColor());
        $this->assertEquals(10, $rectangle->borderSize());
        $this->assertEquals(1000, $rectangle->width());
        $this->assertEquals(2000, $rectangle->height());
    }
}
