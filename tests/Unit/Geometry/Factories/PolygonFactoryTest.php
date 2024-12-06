<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry\Factories;

use Intervention\Image\Geometry\Factories\PolygonFactory;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PolygonFactory::class)]
final class PolygonFactoryTest extends BaseTestCase
{
    public function testFactoryCallback(): void
    {
        $factory = new PolygonFactory(function ($polygon) {
            $polygon->background('fff');
            $polygon->border('ccc', 10);
            $polygon->point(1, 2);
            $polygon->point(3, 4);
            $polygon->point(5, 6);
        });

        $polygon = $factory();
        $this->assertInstanceOf(Polygon::class, $polygon);
        $this->assertTrue($polygon->hasBackgroundColor());
        $this->assertEquals('fff', $polygon->backgroundColor());
        $this->assertEquals('ccc', $polygon->borderColor());
        $this->assertEquals(10, $polygon->borderSize());
        $this->assertEquals(3, $polygon->count());
    }
}
