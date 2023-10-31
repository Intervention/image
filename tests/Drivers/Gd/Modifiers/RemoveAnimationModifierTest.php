<?php

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Modifiers\RemoveAnimationModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Gd\Modifiers\RemoveAnimationModifier
 */
class RemoveAnimationModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testApply(): void
    {
        $image = $this->createTestImage('animation.gif');
        $this->assertEquals(8, count($image));
        $result = $image->modify(new RemoveAnimationModifier(2));
        $this->assertEquals(1, count($image));
        $this->assertEquals(1, count($result));
    }
}
