<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Imagick\Modifiers\PixelateModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Drivers\Imagick\Modifiers\PixelateModifier
 */
class PixelateModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testModify(): void
    {
        $image = $this->createTestImage('trim.png');
        $this->assertEquals('00aef0', $image->getColor(0, 0)->toHex());
        $this->assertEquals('00aef0', $image->getColor(14, 14)->toHex());
        $image->modify(new PixelateModifier(10));
        $this->assertEquals('00aef0', $image->getColor(0, 0)->toHex());
        $this->assertEquals('6bab8c', $image->getColor(14, 14)->toHex());
    }
}
