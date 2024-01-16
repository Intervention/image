<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Modifiers\ColorizeModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Modifiers\ColorizeModifier
 * @covers \Intervention\Image\Drivers\Imagick\Modifiers\ColorizeModifier
 */
class ColorizeModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testModify(): void
    {
        $image = $this->readTestImage('tile.png');
        $image = $image->modify(new ColorizeModifier(100, -100, -100));
        $this->assertColor(251, 0, 0, 255, $image->pickColor(5, 5));
        $this->assertColor(239, 0, 0, 255, $image->pickColor(15, 15));
    }
}
