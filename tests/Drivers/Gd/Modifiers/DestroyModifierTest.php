<?php

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use GdImage;
use Intervention\Image\Drivers\Gd\Modifiers\DestroyModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Gd\Modifiers\DestroyModifier
 */
class DestroyModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testModify(): void
    {
        $image = $this->createTestImage('trim.png');
        $this->assertInstanceOf(GdImage::class, $image->getFrame()->getCore());
        $image->modify(new DestroyModifier());
    }
}
