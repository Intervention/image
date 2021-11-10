<?php

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Drivers\Gd\Modifiers\FitModifier;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

class FitModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testColorChange(): void
    {
        $image = $this->createTestImage('test.jpg');
        $image->resize(800, 600);
        $this->assertEquals(800, $image->width());
        $this->assertEquals(600, $image->height());

        $image->fit(100, 100);

        // $image->modify(new FitModifier(new Size(100, 100)));
        // $this->assertEquals(30, $image->width());
        // $this->assertEquals(20, $image->height());
    }
}
