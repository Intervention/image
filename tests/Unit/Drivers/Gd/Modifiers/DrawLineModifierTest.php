<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\DrawLineModifier;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\GdTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Modifiers\DrawLineModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\DrawLineModifier::class)]
final class DrawLineModifierTest extends GdTestCase
{
    public function testApply(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $line = new Line(new Point(0, 0), new Point(10, 0), 4);
        $line->setBackgroundColor('b53517');
        $image->modify(new DrawLineModifier($line));
        $this->assertEquals('b53517', $image->pickColor(0, 0)->toHex());
    }

    public function testApplyTransparent(): void
    {
        $image = $this->createTestImage(10, 10)->fill('ff5500');
        $this->assertColor(255, 85, 0, 255, $image->pickColor(5, 5));
        $line = new Line(new Point(0, 5), new Point(10, 5), 4);
        $line->setBackgroundColor('fff4');
        $image->modify(new DrawLineModifier($line));
        $this->assertColor(255, 129, 66, 255, $image->pickColor(5, 5));
    }
}
