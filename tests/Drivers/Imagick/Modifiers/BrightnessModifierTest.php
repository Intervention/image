<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Tests\TestCase;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Drivers\Imagick\Modifiers\BrightnessModifier;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

class BrightnessModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testApply(): void
    {
        $image = $this->createTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $image->modify(new BrightnessModifier(30));
        $this->assertEquals('39c9ff', $image->pickColor(14, 14)->toHex());
    }
}
