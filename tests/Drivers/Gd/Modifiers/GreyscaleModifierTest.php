<?php

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Tests\TestCase;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Drivers\Gd\Modifiers\GreyscaleModifier;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

class GreyscaleModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testColorChange(): void
    {
        $image = $this->createTestImage('trim.png');
        $this->assertFalse($image->pickColor(0, 0)->isGreyscale());
        $image->modify(new GreyscaleModifier());
        $this->assertTrue($image->pickColor(0, 0)->isGreyscale());
    }
}
