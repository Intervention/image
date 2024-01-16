<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Modifiers\FillModifier;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Modifiers\FillModifier
 * @covers \Intervention\Image\Drivers\Gd\Modifiers\FillModifier
 */
class FillModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testFloodFillColor(): void
    {
        $image = $this->readTestImage('blocks.png');
        $this->assertEquals('0000ff', $image->pickColor(420, 270)->toHex());
        $this->assertEquals('ff0000', $image->pickColor(540, 400)->toHex());
        $image->modify(new FillModifier(new Color(204, 204, 204), new Point(540, 400)));
        $this->assertEquals('0000ff', $image->pickColor(420, 270)->toHex());
        $this->assertEquals('cccccc', $image->pickColor(540, 400)->toHex());
    }

    public function testFillAllColor(): void
    {
        $image = $this->readTestImage('blocks.png');
        $this->assertEquals('0000ff', $image->pickColor(420, 270)->toHex());
        $this->assertEquals('ff0000', $image->pickColor(540, 400)->toHex());
        $image->modify(new FillModifier(new Color(204, 204, 204)));
        $this->assertEquals('cccccc', $image->pickColor(420, 270)->toHex());
        $this->assertEquals('cccccc', $image->pickColor(540, 400)->toHex());
    }
}
