<?php

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Modifiers\ContainModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Modifiers\ContainModifier
 */
class ContainModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testModify(): void
    {
        $image = $this->readTestImage('blocks.png');
        $this->assertEquals(640, $image->width());
        $this->assertEquals(480, $image->height());
        $image->modify(new ContainModifier(200, 100, 'ff0'));
        $this->assertEquals(200, $image->width());
        $this->assertEquals(100, $image->height());
        $this->assertColor(255, 255, 0, 255, $image->pickColor(0, 0));
        $this->assertColor(255, 255, 0, 0, $image->pickColor(140, 10)); // transparent
        $this->assertColor(255, 255, 0, 255, $image->pickColor(175, 10));
    }
}
