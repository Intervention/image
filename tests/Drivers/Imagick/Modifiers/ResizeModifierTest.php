<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Imagick\Modifiers\ResizeModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 */
class ResizeModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testModify(): void
    {
        $image = $this->createTestImage('blocks.png');
        $this->assertEquals(640, $image->getWidth());
        $this->assertEquals(480, $image->getHeight());
        $image->modify(new ResizeModifier(200, 100));
        $this->assertEquals(200, $image->getWidth());
        $this->assertEquals(100, $image->getHeight());
        $this->assertColor(255, 0, 0, 1, $image->pickColor(150, 70));
        $this->assertColor(0, 255, 0, 1, $image->pickColor(125, 70));
        $this->assertColor(0, 0, 255, 1, $image->pickColor(130, 54));
        $this->assertTransparency($image->pickColor(150, 45));
    }
}
