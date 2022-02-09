<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Imagick\Modifiers\PlaceModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 */
class PlaceModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testColorChange(): void
    {
        $image = $this->createTestImage('test.jpg');
        $this->assertEquals('febc44', $image->pickColor(300, 25)->toHex());
        $image->modify(new PlaceModifier(__DIR__ . '/../../../images/circle.png', 'top-right', 0, 0));
        $this->assertEquals('33260e', $image->pickColor(300, 25)->toHex());
    }
}
