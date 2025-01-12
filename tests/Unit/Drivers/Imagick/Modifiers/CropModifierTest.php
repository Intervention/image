<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use Intervention\Image\Colors\Cmyk\Colorspace;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\CropModifier;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\CropModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\CropModifier::class)]
final class CropModifierTest extends ImagickTestCase
{
    public function testModify(): void
    {
        $image = $this->readTestImage('blocks.png');
        $image = $image->modify(new CropModifier(200, 200, 0, 0, 'ffffff', 'bottom-right'));
        $this->assertEquals(200, $image->width());
        $this->assertEquals(200, $image->height());
        $this->assertColor(255, 0, 0, 255, $image->pickColor(5, 5));
        $this->assertColor(255, 0, 0, 255, $image->pickColor(100, 100));
        $this->assertColor(255, 0, 0, 255, $image->pickColor(190, 190));
    }

    public function testModifyExtend(): void
    {
        $image = $this->readTestImage('blocks.png');
        $image = $image->modify(new CropModifier(800, 100, -10, -10, 'ff0000', 'top-left'));
        $this->assertEquals(800, $image->width());
        $this->assertEquals(100, $image->height());
        $this->assertColor(255, 0, 0, 255, $image->pickColor(9, 9));
        $this->assertColor(0, 0, 255, 255, $image->pickColor(16, 16));
        $this->assertColor(0, 0, 255, 255, $image->pickColor(445, 16));
        $this->assertTransparency($image->pickColor(460, 16));
    }

    public function testModifySinglePixel(): void
    {
        $image = $this->createTestImage(1, 1);
        $this->assertEquals(1, $image->width());
        $this->assertEquals(1, $image->height());
        $image->modify(new CropModifier(3, 3, 0, 0, 'ff0', 'center'));
        $this->assertEquals(3, $image->width());
        $this->assertEquals(3, $image->height());
        $this->assertColor(255, 255, 0, 255, $image->pickColor(0, 0));
        $this->assertColor(255, 0, 0, 255, $image->pickColor(1, 1));
        $this->assertColor(255, 255, 0, 255, $image->pickColor(2, 2));
    }

    public function testModifyKeepsColorspace(): void
    {
        $image = $this->readTestImage('cmyk.jpg');
        $this->assertInstanceOf(Colorspace::class, $image->colorspace());
        $image = $image->modify(new CropModifier(800, 100, -10, -10, 'ff0000'));
        $this->assertInstanceOf(Colorspace::class, $image->colorspace());
    }

    public function testModifyKeepsResolution(): void
    {
        $image = $this->readTestImage('300dpi.png');
        $this->assertEquals(300, round($image->resolution()->perInch()->x()));
        $image = $image->modify(new CropModifier(800, 100, -10, -10, 'ff0000'));
        $this->assertEquals(300, round($image->resolution()->perInch()->x()));
    }

    public function testHalfTransparent(): void
    {
        $image = $this->createTestImage(16, 16);
        $image->modify(new CropModifier(32, 32, 0, 0, '00f5', 'center'));
        $this->assertEquals(32, $image->width());
        $this->assertEquals(32, $image->height());
        $this->assertColor(0, 0, 255, 77, $image->pickColor(5, 5));
        $this->assertColor(0, 0, 255, 77, $image->pickColor(16, 5));
        $this->assertColor(0, 0, 255, 77, $image->pickColor(30, 5));
        $this->assertColor(0, 0, 255, 77, $image->pickColor(5, 16));
        $this->assertColor(255, 0, 0, 255, $image->pickColor(16, 16));
        $this->assertColor(0, 0, 255, 77, $image->pickColor(30, 16));
        $this->assertColor(0, 0, 255, 77, $image->pickColor(5, 30));
        $this->assertColor(0, 0, 255, 77, $image->pickColor(16, 30));
        $this->assertColor(0, 0, 255, 77, $image->pickColor(30, 30));
    }

    public function testMergeTransparentBackgrounds(): void
    {
        $image = $this->createTestImage(1, 1)->fill('f00');
        $this->assertEquals(1, $image->width());
        $this->assertEquals(1, $image->height());
        $image->modify(new CropModifier(3, 3, 0, 0, '00f7', 'center'));
        $this->assertEquals(3, $image->width());
        $this->assertEquals(3, $image->height());
        $this->assertColor(0, 0, 255, 127, $image->pickColor(0, 0), 1);
        $this->assertColor(255, 0, 0, 255, $image->pickColor(1, 1));
        $this->assertColor(0, 0, 255, 127, $image->pickColor(2, 2), 1);
    }
}
