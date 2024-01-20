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

    public function testFloodFillImage(): void
    {
        $image = $this->readTestImage('blocks.png');
        $this->assertTransparency($image->pickColor(445, 11));
        $this->assertTransparency($image->pickColor(454, 4));
        $this->assertTransparency($image->pickColor(460, 28));
        $this->assertTransparency($image->pickColor(470, 20));
        $this->assertTransparency($image->pickColor(470, 30));
        $image->modify(new FillModifier($this->getTestImagePath('tile.png'), new Point(500, 0)));
        $this->assertEquals('445160', $image->pickColor(445, 11)->toHex());
        $this->assertEquals('b4e000', $image->pickColor(454, 4)->toHex());
        $this->assertEquals('445160', $image->pickColor(460, 28)->toHex());
        $this->assertEquals('b4e000', $image->pickColor(470, 20)->toHex());
        $this->assertTransparency($image->pickColor(470, 30));
    }

    public function testFillAllImage(): void
    {
        $image = $this->readTestImage('blocks.png');
        $this->assertEquals('0000ff', $image->pickColor(0, 0)->toHex());
        $this->assertEquals('0000ff', $image->pickColor(12, 5)->toHex());
        $this->assertEquals('0000ff', $image->pickColor(12, 12)->toHex());
        $this->assertTransparency($image->pickColor(445, 11));
        $this->assertTransparency($image->pickColor(454, 4));
        $this->assertTransparency($image->pickColor(460, 28));
        $this->assertTransparency($image->pickColor(470, 20));
        $this->assertTransparency($image->pickColor(470, 30));
        $image->modify(new FillModifier($this->getTestImagePath('tile.png')));
        $this->assertEquals('b4e000', $image->pickColor(0, 0)->toHex());
        $this->assertEquals('0000ff', $image->pickColor(12, 5)->toHex());
        $this->assertEquals('445160', $image->pickColor(12, 12)->toHex());
        $this->assertEquals('445160', $image->pickColor(445, 11)->toHex());
        $this->assertEquals('b4e000', $image->pickColor(454, 4)->toHex());
        $this->assertEquals('445160', $image->pickColor(460, 28)->toHex());
        $this->assertEquals('b4e000', $image->pickColor(470, 20)->toHex());
        $this->assertTransparency($image->pickColor(470, 30));
    }
}
