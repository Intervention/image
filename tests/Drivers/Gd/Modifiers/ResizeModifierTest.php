<?php

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Drivers\Gd\Modifiers\ResizeModifier;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

class ResizeModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testColorChange(): void
    {
        $image = $this->createTestImage('trim.png');
        $this->assertEquals(50, $image->width());
        $this->assertEquals(50, $image->height());
        $image->modify(new ResizeModifier(new Size(300, 100)));
        $this->assertEquals(300, $image->width());
        $this->assertEquals(100, $image->height());
    }
}
