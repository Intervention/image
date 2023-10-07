<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Imagick\Modifiers\FlipModifier;
use Intervention\Image\Drivers\Imagick\Modifiers\FlopModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Drivers\Imagick\Modifiers\FlipModifier
 * @covers \Intervention\Image\Drivers\Imagick\Modifiers\FlopModifier
 */
class FlipFlopModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testFlipImage(): void
    {
        $image = $this->createTestImage('tile.png');
        $this->assertEquals('b4e000', $image->getColor(0, 0)->toHex());
        $image->modify(new FlipModifier());
        $this->assertEquals('000000', $image->getColor(0, 0)->toHex());
    }

    public function testFlopImage(): void
    {
        $image = $this->createTestImage('tile.png');
        $this->assertEquals('b4e000', $image->getColor(0, 0)->toHex());
        $image->modify(new FlopModifier());
        $this->assertEquals('000000', $image->getColor(0, 0)->toHex());
    }
}
