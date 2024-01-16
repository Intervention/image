<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Modifiers\ResizeModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Modifiers\ResizeModifier
 * @covers \Intervention\Image\Drivers\Gd\Modifiers\ResizeModifier
 */
class ResizeModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testModify(): void
    {
        $image = $this->readTestImage('blocks.png');
        $this->assertEquals(640, $image->width());
        $this->assertEquals(480, $image->height());
        $image->modify(new ResizeModifier(200, 100));
        $this->assertEquals(200, $image->width());
        $this->assertEquals(100, $image->height());
        $this->assertColor(255, 0, 0, 255, $image->pickColor(150, 70));
        $this->assertColor(0, 255, 0, 255, $image->pickColor(125, 70));
        $this->assertColor(0, 0, 255, 255, $image->pickColor(130, 54));
        $this->assertTransparency($image->pickColor(170, 30));
    }
}
