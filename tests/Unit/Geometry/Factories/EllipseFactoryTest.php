<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry\Factories;

use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Factories\EllipseFactory;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(EllipseFactory::class)]
final class EllipseFactoryTest extends BaseTestCase
{
    public function testFactoryCallback(): void
    {
        $factory = new EllipseFactory(function (EllipseFactory $ellipse): void {
            $ellipse->background('fff');
            $ellipse->border('ccc', 10);
            $ellipse->width(100);
            $ellipse->height(200);
            $ellipse->size(1000, 2000);
            $ellipse->at(20, 30);
        });

        $ellipse = $factory->drawable();
        $this->assertInstanceOf(Ellipse::class, $ellipse);
        $this->assertTrue($ellipse->hasBackgroundColor());
        $this->assertEquals('fff', $ellipse->backgroundColor());
        $this->assertEquals('ccc', $ellipse->borderColor());
        $this->assertEquals(10, $ellipse->borderSize());
        $this->assertEquals(1000, $ellipse->width());
        $this->assertEquals(2000, $ellipse->height());
        $this->assertEquals(20, $ellipse->position()->x());
        $this->assertEquals(30, $ellipse->position()->y());
    }

    public function testBuild(): void
    {
        $ellipse = EllipseFactory::build(function (EllipseFactory $ellipse): void {
            $ellipse->background('fff');
            $ellipse->border('ccc', 10);
            $ellipse->width(100);
            $ellipse->height(200);
            $ellipse->size(1000, 2000);
            $ellipse->at(20, 30);
        });

        $this->assertInstanceOf(Ellipse::class, $ellipse);
        $this->assertTrue($ellipse->hasBackgroundColor());
        $this->assertEquals('fff', $ellipse->backgroundColor());
        $this->assertEquals('ccc', $ellipse->borderColor());
        $this->assertEquals(10, $ellipse->borderSize());
        $this->assertEquals(1000, $ellipse->width());
        $this->assertEquals(2000, $ellipse->height());
        $this->assertEquals(20, $ellipse->position()->x());
        $this->assertEquals(30, $ellipse->position()->y());
    }
}
