<?php

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Drivers\Gd\Modifiers\BlurModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

class BlurModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testColorChange(): void
    {
        $image = $this->createTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $image->modify(new BlurModifier(30));
        $this->assertEquals('4fa68d', $image->pickColor(14, 14)->toHex());
    }
}
