<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Modifiers\ResizeModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Modifiers\ResizeModifier
 * @covers \Intervention\Image\Drivers\Imagick\Modifiers\ResizeModifier
 */
class ResizeModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testModify(): void
    {
        $image = $this->readTestImage('blocks.png');
        $this->assertEquals(640, $image->width());
        $this->assertEquals(480, $image->height());
        $image->modify(new ResizeModifier(200, 100));
        $this->assertEquals(200, $image->width());
        $this->assertEquals(100, $image->height());
        $this->assertColor(255, 0, 0, 255, $image->pickColor(150, 70));
    }
}
