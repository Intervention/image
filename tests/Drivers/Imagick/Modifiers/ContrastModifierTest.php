<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Tests\TestCase;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Drivers\Imagick\Modifiers\ContrastModifier;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

class ContrastModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testApply(): void
    {
        $image = $this->createTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $image->modify(new ContrastModifier(30));
        $this->assertEquals('00fcff', $image->pickColor(14, 14)->toHex());
    }
}
