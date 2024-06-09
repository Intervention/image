<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\DrawBezierModifier;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Bezier;
use Intervention\Image\Tests\GdTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Modifiers\DrawPixelModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\DrawPixelModifier::class)]
final class DrawBezierModifierTest extends GdTestCase
{
    public function testApply(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $drawable = new Bezier([
            new Point(0, 0),
            new Point(15, 0),
            new Point(15, 15),
            new Point(0, 15)
        ]);
        $drawable->setBackgroundColor('b53717');
        $image->modify(new DrawBezierModifier($drawable));
        $this->assertEquals('b53717', $image->pickColor(5, 5)->toHex());
    }
}
