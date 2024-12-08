<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry\Factories;

use Intervention\Image\Geometry\Factories\BezierFactory;
use Intervention\Image\Geometry\Bezier;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(BezierFactory::class)]
final class BezierFactoryTest extends BaseTestCase
{
    public function testFactoryCallback(): void
    {
        $factory = new BezierFactory(function ($bezier) {
            $bezier->background('f00');
            $bezier->border('ff0', 10);
            $bezier->point(300, 260);
            $bezier->point(150, 335);
            $bezier->point(300, 410);
        });

        $bezier = $factory();
        $this->assertInstanceOf(Bezier::class, $bezier);
        $this->assertTrue($bezier->hasBackgroundColor());
        $this->assertEquals('f00', $bezier->backgroundColor());
        $this->assertEquals('ff0', $bezier->borderColor());
        $this->assertEquals(10, $bezier->borderSize());
        $this->assertEquals(3, $bezier->count());
    }
}
