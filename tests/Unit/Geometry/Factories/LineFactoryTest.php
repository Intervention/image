<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry\Factories;

use Intervention\Image\Geometry\Factories\LineFactory;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(LineFactory::class)]
final class LineFactoryTest extends BaseTestCase
{
    public function testFactoryCallback(): void
    {
        $factory = new LineFactory(function ($line) {
            $line->color('fff');
            $line->background('fff');
            $line->border('fff', 10);
            $line->width(10);
            $line->from(100, 200);
            $line->to(300, 400);
        });

        $line = $factory();
        $this->assertInstanceOf(Line::class, $line);
        $this->assertTrue($line->hasBackgroundColor());
        $this->assertEquals('fff', $line->backgroundColor());
        $this->assertEquals(100, $line->start()->x());
        $this->assertEquals(200, $line->start()->y());
        $this->assertEquals(300, $line->end()->x());
        $this->assertEquals(400, $line->end()->y());
        $this->assertEquals(10, $line->width());
    }
}
