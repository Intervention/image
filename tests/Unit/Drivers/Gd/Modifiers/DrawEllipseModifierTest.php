<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\DrawEllipseModifier;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\GdTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Modifiers\DrawEllipseModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\DrawEllipseModifier::class)]
final class DrawEllipseModifierTest extends GdTestCase
{
    public function testApply(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $drawable = new Ellipse(10, 10, new Point(14, 14));
        $drawable->setBackgroundColor('b53717');
        $image->modify(new DrawEllipseModifier($drawable));
        $this->assertEquals('b53717', $image->pickColor(14, 14)->toHex());
    }
}
