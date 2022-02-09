<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Imagick\Modifiers\FitModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 */
class FitModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testModify(): void
    {
        $image = $this->createTestImage('blocks.png');
        $this->assertEquals(640, $image->getWidth());
        $this->assertEquals(480, $image->getHeight());
        $image->modify(new FitModifier(100, 100, 'center'));
        $this->assertEquals(100, $image->getWidth());
        $this->assertEquals(100, $image->getHeight());
        $this->assertColor(255, 0, 0, 1, $image->pickColor(90, 90));
        $this->assertColor(0, 255, 0, 1, $image->pickColor(65, 70));
        $this->assertColor(0, 0, 255, 1, $image->pickColor(70, 52));
        $this->assertTransparency($image->pickColor(90, 30));
    }
}
