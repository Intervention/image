<?php

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Modifiers\FlipModifier;
use Intervention\Image\Modifiers\FlopModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Modifiers\FlipModifier
 * @covers \Intervention\Image\Modifiers\FlopModifier
 */
class FlipFlopModifierTest extends TestCase
{
    use CanCreateGdTestImage;

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
