<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Drivers\Imagick\Modifiers\DestroyModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @covers \Intervention\Image\Drivers\Imagick\Modifiers\DestroyModifier
 */
class DestroyModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testModify(): void
    {
        $image = $this->createTestImage('trim.png');
        $this->assertInstanceOf(Imagick::class, $image->getFrame()->getCore());
        $image->modify(new DestroyModifier());
    }
}
