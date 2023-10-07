<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Imagick\Modifiers\GammaModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Drivers\Imagick\Modifiers\GammaModifier
 */
class GammaModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testModifier(): void
    {
        $image = $this->createTestImage('trim.png');
        $this->assertEquals('00aef0', $image->getColor(0, 0)->toHex());
        $image->modify(new GammaModifier(2.1));
        $this->assertEquals('00d5f8', $image->getColor(0, 0)->toHex());
    }
}
