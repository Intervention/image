<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Imagick\Modifiers\DrawRectangleModifier;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

class DrawRectangleModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testApply(): void
    {
        $image = $this->createTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $rectangle = new Rectangle(300, 200);
        $rectangle->background('ffffff');
        $image->modify(new DrawRectangleModifier(new Point(14, 14), $rectangle));
        $this->assertEquals('ffffff', $image->pickColor(14, 14)->toHex());
    }
}
