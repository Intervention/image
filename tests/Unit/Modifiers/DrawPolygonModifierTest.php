<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Modifiers;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Modifiers\DrawPolygonModifier;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(DrawPolygonModifier::class)]
final class DrawPolygonModifierTest extends BaseTestCase
{
    public function testConstructorThrowsWithLessThanThreePoints(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new DrawPolygonModifier(new Polygon([new Point(0, 0), new Point(1, 1)]));
    }

    public function testConstructorWithThreePoints(): void
    {
        $modifier = new DrawPolygonModifier(
            new Polygon([new Point(0, 0), new Point(1, 1), new Point(2, 0)])
        );
        $this->assertInstanceOf(DrawPolygonModifier::class, $modifier);
    }
}
