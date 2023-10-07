<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Imagick\Modifiers\DrawEllipseModifier;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

class DrawEllipseModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testApply(): void
    {
        $image = $this->createTestImage('trim.png');
        $this->assertEquals('00aef0', $image->getColor(14, 14)->toHex());
        $drawable = new Ellipse(10, 10);
        $drawable->background('b53717');
        $image->modify(new DrawEllipseModifier(new Point(14, 14), $drawable));
        $this->assertEquals('b53717', $image->getColor(14, 14)->toHex());
    }
}
