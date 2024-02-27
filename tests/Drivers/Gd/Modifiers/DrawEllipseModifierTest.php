<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Requires;
use Intervention\Image\Modifiers\DrawEllipseModifier;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

#[Requires('extension gd')]
#[CoversClass(\Intervention\Image\Modifiers\DrawEllipseModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\DrawEllipseModifier::class)]
final class DrawEllipseModifierTest extends TestCase
{
    use CanCreateGdTestImage;

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
