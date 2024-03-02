<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers;

use Intervention\Image\Drivers\AbstractTextModifier;
use Intervention\Image\Geometry\Point;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Typography\Font;
use Mockery;

#[CoversClass(AbstractTextModifier::class)]
final class AbstractTextModifierTest extends BaseTestCase
{
    public function testStrokeOffsets(): void
    {
        $modifier = Mockery::mock(AbstractTextModifier::class)->makePartial();
        $this->assertEquals([
        ], $modifier->strokeOffsets(
            new Font()
        ));

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
        ], $modifier->strokeOffsets(
            (new Font())->setStrokeWidth(1)
        ));
    }
}
