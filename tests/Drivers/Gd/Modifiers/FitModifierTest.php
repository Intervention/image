<?php

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Drivers\Gd\Modifiers\FitModifier;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

class FitModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testModify(): void
    {
        $image = $this->createTestImage('blocks.png');
        $this->assertEquals(640, $image->getWidth());
        $this->assertEquals(480, $image->getHeight());
        $image->modify(new FitModifier(100, 100, 'center'));
        $this->assertEquals(100, $image->getWidth());
        $this->assertEquals(100, $image->getHeight());
        $this->assertColor(255, 0, 0, 1, $image->pickColor(90, 90));
        $this->assertColor(0, 255, 0, 1, $image->pickColor(65, 70));
        $this->assertColor(0, 0, 255, 1, $image->pickColor(70, 52));
        $this->assertTransparency($image->pickColor(90, 30));
    }
}
