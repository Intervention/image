<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Tests\TestCase;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Drivers\Imagick\Modifiers\GreyscaleModifier;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

class GreyscaleModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testColorChange(): void
    {
        $image = $this->createTestImage('trim.png');
        $this->assertFalse($image->pickColor(0, 0)->isGreyscale());
        $image->modify(new GreyscaleModifier());
        $this->assertTrue($image->pickColor(0, 0)->isGreyscale());
    }
}
