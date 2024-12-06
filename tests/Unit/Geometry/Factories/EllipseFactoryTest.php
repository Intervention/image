<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry\Factories;

use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Factories\EllipseFactory;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(EllipseFactory::class)]
final class EllipseFactoryTest extends BaseTestCase
{
    public function testFactoryCallback(): void
    {
        $factory = new EllipseFactory(new Point(1, 2), function ($ellipse) {
            $ellipse->background('fff');
            $ellipse->border('ccc', 10);
            $ellipse->width(100);
            $ellipse->height(200);
            $ellipse->size(1000, 2000);
        });

        $ellipse = $factory();
        $this->assertInstanceOf(Ellipse::class, $ellipse);
        $this->assertTrue($ellipse->hasBackgroundColor());
        $this->assertEquals('fff', $ellipse->backgroundColor());
        $this->assertEquals('ccc', $ellipse->borderColor());
        $this->assertEquals(10, $ellipse->borderSize());
        $this->assertEquals(1000, $ellipse->width());
        $this->assertEquals(2000, $ellipse->height());
    }
}
