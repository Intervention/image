<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry\Factories;

use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Factories\CircleFactory;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(CircleFactory::class)]
final class CircleFactoryTest extends BaseTestCase
{
    public function testFactoryCallback(): void
    {
        $factory = new CircleFactory(function (CircleFactory $circle): void {
            $circle->background('fff');
            $circle->border('ccc', 10);
            $circle->radius(100);
            $circle->diameter(1000);
            $circle->at(20, 30);
        });

        $circle = $factory->drawable();
        $this->assertInstanceOf(Ellipse::class, $circle);
        $this->assertTrue($circle->hasBackgroundColor());
        $this->assertEquals('fff', $circle->backgroundColor());
        $this->assertEquals('ccc', $circle->borderColor());
        $this->assertEquals(10, $circle->borderSize());
        $this->assertEquals(1000, $circle->width());
        $this->assertEquals(1000, $circle->height());
        $this->assertEquals(20, $circle->position()->x());
        $this->assertEquals(30, $circle->position()->y());
    }

    public function testBuild(): void
    {
        $circle = CircleFactory::build(function (CircleFactory $circle): void {
            $circle->background('fff');
            $circle->border('ccc', 10);
            $circle->radius(100);
            $circle->diameter(1000);
            $circle->at(20, 30);
        });

        $this->assertInstanceOf(Ellipse::class, $circle);
        $this->assertTrue($circle->hasBackgroundColor());
        $this->assertEquals('fff', $circle->backgroundColor());
        $this->assertEquals('ccc', $circle->borderColor());
        $this->assertEquals(10, $circle->borderSize());
        $this->assertEquals(1000, $circle->width());
        $this->assertEquals(1000, $circle->height());
        $this->assertEquals(20, $circle->position()->x());
        $this->assertEquals(30, $circle->position()->y());
    }
}
