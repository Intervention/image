<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Drivers\Imagick\Modifiers\FillModifier;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Drivers\Imagick\Modifiers\FillModifier
 */
class FillModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testFloodFillColor(): void
    {
        $image = $this->createTestImage('blocks.png');
        $this->assertEquals('0000ff', $image->getColor(420, 270)->toHex());
        $this->assertEquals('ff0000', $image->getColor(540, 400)->toHex());
        $image->modify(new FillModifier(new Color(new ImagickPixel('#cccccc')), new Point(540, 400)));
        $this->assertEquals('0000ff', $image->getColor(420, 270)->toHex());
        $this->assertEquals('cccccc', $image->getColor(540, 400)->toHex());
    }

    public function testFillAllColor(): void
    {
        $image = $this->createTestImage('blocks.png');
        $this->assertEquals('0000ff', $image->getColor(420, 270)->toHex());
        $this->assertEquals('ff0000', $image->getColor(540, 400)->toHex());
        $image->modify(new FillModifier(new Color(new ImagickPixel('#cccccc'))));
        $this->assertEquals('cccccc', $image->getColor(420, 270)->toHex());
        $this->assertEquals('cccccc', $image->getColor(540, 400)->toHex());
    }
}
