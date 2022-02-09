<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Imagick\Modifiers\BlurModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 */
class BlurModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testColorChange(): void
    {
        $image = $this->createTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $image->modify(new BlurModifier(30));
        $this->assertEquals('42acb2', $image->pickColor(14, 14)->toHex());
    }
}
