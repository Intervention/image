<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Modifiers\BlurModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Modifiers\BlurModifier
 * @covers \Intervention\Image\Drivers\Imagick\Modifiers\BlurModifier
 */
class BlurModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testColorChange(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $image->modify(new BlurModifier(30));
        $this->assertEquals('42acb2', $image->pickColor(14, 14)->toHex());
    }
}
