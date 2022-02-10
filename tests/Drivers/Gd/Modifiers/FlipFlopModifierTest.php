<?php

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Modifiers\FlipModifier;
use Intervention\Image\Drivers\Gd\Modifiers\FlopModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Gd\Modifiers\FlipModifier
 * @covers \Intervention\Image\Drivers\Gd\Modifiers\FlopModifier
 */
class FlipFlopModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testFlipImage(): void
    {
        $image = $this->createTestImage('tile.png');
        $this->assertEquals('b4e000', $image->pickColor(0, 0)->toHex());
        $image->modify(new FlipModifier());
        $this->assertEquals('000000', $image->pickColor(0, 0)->toHex());
    }

    public function testFlopImage(): void
    {
        $image = $this->createTestImage('tile.png');
        $this->assertEquals('b4e000', $image->pickColor(0, 0)->toHex());
        $image->modify(new FlopModifier());
        $this->assertEquals('000000', $image->pickColor(0, 0)->toHex());
    }
}
