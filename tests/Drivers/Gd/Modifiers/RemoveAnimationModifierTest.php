<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Modifiers\RemoveAnimationModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Modifiers\RemoveAnimationModifier
 * @covers \Intervention\Image\Drivers\Gd\Modifiers\RemoveAnimationModifier
 */
class RemoveAnimationModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testApply(): void
    {
        $image = $this->readTestImage('animation.gif');
        $this->assertEquals(8, count($image));
        $result = $image->modify(new RemoveAnimationModifier(2));
        $this->assertEquals(1, count($image));
        $this->assertEquals(1, count($result));
    }

    public function testApplyPercent(): void
    {
        $image = $this->readTestImage('animation.gif');
        $this->assertEquals(8, count($image));
        $result = $image->modify(new RemoveAnimationModifier('20%'));
        $this->assertEquals(1, count($image));
        $this->assertEquals(1, count($result));
    }
}
