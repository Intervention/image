<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Imagick\Modifiers\RemoveAnimationModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Drivers\Imagick\Modifiers\RemoveAnimationModifier
 */
class RemoveAnimationModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testApply(): void
    {
        $image = $this->createTestImage('animation.gif');
        $this->assertEquals(8, count($image));
        $image = $image->modify(new RemoveAnimationModifier(2));
        $this->assertEquals(1, count($image));
    }
}
