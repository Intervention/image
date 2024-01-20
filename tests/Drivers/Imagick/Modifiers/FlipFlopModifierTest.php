<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Modifiers\FlipModifier;
use Intervention\Image\Modifiers\FlopModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Modifiers\FlipModifier
 * @covers \Intervention\Image\Modifiers\FlopModifier
 * @covers \Intervention\Image\Drivers\Imagick\Modifiers\FlipModifier
 * @covers \Intervention\Image\Drivers\Imagick\Modifiers\FlopModifier
 */
class FlipFlopModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testFlipImage(): void
    {
        $image = $this->readTestImage('tile.png');
        $this->assertEquals('b4e000', $image->pickColor(0, 0)->toHex());
        $image->modify(new FlipModifier());
        $this->assertEquals('00000000', $image->pickColor(0, 0)->toHex());
    }

    public function testFlopImage(): void
    {
        $image = $this->readTestImage('tile.png');
        $this->assertEquals('b4e000', $image->pickColor(0, 0)->toHex());
        $image->modify(new FlopModifier());
        $this->assertEquals('00000000', $image->pickColor(0, 0)->toHex());
    }
}
