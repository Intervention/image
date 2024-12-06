<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Modifiers;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Modifiers\TextModifier;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Typography\Font;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TextModifier::class)]
final class TextModifierTest extends BaseTestCase
{
    public function testStrokeOffsets(): void
    {
        $modifier = new class ('test', new Point(), new Font()) extends TextModifier
        {
            public function testStrokeOffsets($font)
            {
                return $this->strokeOffsets($font);
            }
        };

        $this->assertEquals([], $modifier->testStrokeOffsets(new Font()));

        $this->assertEquals([
            new Point(-1, -1),
            new Point(-1, 0),
            new Point(-1, 1),
            new Point(0, -1),
            new Point(0, 0),
            new Point(0, 1),
            new Point(1, -1),
            new Point(1, 0),
            new Point(1, 1),
        ], $modifier->testStrokeOffsets((new Font())->setStrokeWidth(1)));
    }
}
