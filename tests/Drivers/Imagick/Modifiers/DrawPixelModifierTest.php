<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Modifiers\DrawPixelModifier;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Modifiers\DrawPixelModifier
 * @covers \Intervention\Image\Drivers\Imagick\Modifiers\DrawPixelModifier
 */
class DrawPixelModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testApply(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $image->modify(new DrawPixelModifier(new Point(14, 14), 'ffffff'));
        $this->assertEquals('ffffff', $image->pickColor(14, 14)->toHex());
    }
}
