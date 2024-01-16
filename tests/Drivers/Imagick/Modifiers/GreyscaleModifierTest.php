<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Modifiers\GreyscaleModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Modifiers\GreyscaleModifier
 * @covers \Intervention\Image\Drivers\Imagick\Modifiers\GreyscaleModifier
 */
class GreyscaleModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testColorChange(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertFalse($image->pickColor(0, 0)->isGreyscale());
        $image->modify(new GreyscaleModifier());
        $this->assertTrue($image->pickColor(0, 0)->isGreyscale());
    }
}
