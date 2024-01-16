<?php
namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Modifiers\TrimModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

class TrimModifierTest extends TestCase {
    use CanCreateImagickTestImage;

    public function testTrimImage(): void {
        $image = $this->readTestImage('softuni.png');
        $this->assertEquals(280, $image->width());
        $this->assertEquals(280, $image->height());
        $image->modify(new TrimModifier(10));

        $this->assertEquals(244, $image->width());
        $this->assertEquals(245, $image->height());
    }
}