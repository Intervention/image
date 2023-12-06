<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Modifiers\DrawPolygonModifier;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

class DrawPolygonModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testApply(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $drawable = new Polygon([new Point(0, 0), new Point(15, 15), new Point(20, 20)]);
        $drawable->setBackgroundColor('b53717');
        $image->modify(new DrawPolygonModifier($drawable));
        $this->assertEquals('b53717', $image->pickColor(14, 14)->toHex());
    }
}
