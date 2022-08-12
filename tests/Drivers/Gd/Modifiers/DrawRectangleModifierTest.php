<?php

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Modifiers\DrawRectangleModifier;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Gd\Modifiers\DrawRectangleModifier
 */
class DrawRectangleModifierTest extends TestCase
{
    use CanCreateGdTestImage;

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
