<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Modifiers\QuantizeColorsModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Modifiers\QuantizeColorsModifier
 * @covers \Intervention\Image\Drivers\Imagick\Modifiers\QuantizeColorsModifier
 */
class QuantizeColorsModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testColorChange(): void
    {
        $image = $this->readTestImage('gradient.bmp');
        $this->assertEquals(15, $image->core()->native()->getImageColors());
        $image->modify(new QuantizeColorsModifier(4));
        $this->assertEquals(4, $image->core()->native()->getImageColors());
    }
}
