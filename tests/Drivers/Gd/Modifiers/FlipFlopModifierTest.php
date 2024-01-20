<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Modifiers\FlipModifier;
use Intervention\Image\Modifiers\FlopModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Modifiers\FlipModifier
 * @covers \Intervention\Image\Drivers\Gd\Modifiers\FlipModifier
 */
class FlipFlopModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testFlipImage(): void
    {
        $image = $this->readTestImage('tile.png');
        $this->assertEquals('b4e000', $image->pickColor(0, 0)->toHex());
        $image->modify(new FlipModifier());
        $this->assertTransparency($image->pickColor(0, 0));
    }

    public function testFlopImage(): void
    {
        $image = $this->readTestImage('tile.png');
        $this->assertEquals('b4e000', $image->pickColor(0, 0)->toHex());
        $image->modify(new FlopModifier());
        $this->assertTransparency($image->pickColor(0, 0));
    }
}
