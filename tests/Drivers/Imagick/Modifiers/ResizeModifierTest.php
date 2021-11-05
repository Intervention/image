<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Drivers\Imagick\Modifiers\ResizeModifier;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

class ResizeModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testColorChange(): void
    {
        $image = $this->createTestImage('trim.png');
        $this->assertEquals(50, $image->width());
        $this->assertEquals(50, $image->height());
        $image->modify(new ResizeModifier(new Size(30, 20)));
        $this->assertEquals(30, $image->width());
        $this->assertEquals(20, $image->height());
    }
}
