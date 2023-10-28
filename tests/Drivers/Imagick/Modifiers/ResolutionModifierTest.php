<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Imagick\Modifiers\ResolutionModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Imagick\Modifiers\ResolutionModifier
 */
class ResolutionModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testResolutionChange(): void
    {
        $image = $this->createTestImage('test.jpg');
        $this->assertEquals(72.0, $image->resolution()->x());
        $this->assertEquals(72.0, $image->resolution()->y());
        $image->modify(new ResolutionModifier(1, 2));
        $this->assertEquals(1.0, $image->resolution()->x());
        $this->assertEquals(2.0, $image->resolution()->y());
    }
}
