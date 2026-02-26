<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick;

use Imagick;
use ImagickPixel;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Hsl\Colorspace as HslColorspace;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Hsv\Colorspace as HsvColorspace;
use Intervention\Image\Colors\Oklab\Color as OklabColor;
use Intervention\Image\Colors\Oklab\Colorspace as OklabColorspace;
use Intervention\Image\Colors\Oklch\Color as OklchColor;
use Intervention\Image\Colors\Oklch\Colorspace as OklchColorspace;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Drivers\Imagick\ColorProcessor;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('imagick')]
#[CoversClass(ColorProcessor::class)]
final class ColorProcessorTest extends BaseTestCase
{
    public function testColorToNative(): void
    {
        $processor = new ColorProcessor(new Colorspace());
        $result = $processor->colorToNative(new Color(255, 55, 0, .2));
        $this->assertInstanceOf(ImagickPixel::class, $result);
        $this->assertEquals(['r' => 1, 'g' => 0.21568627450980393, 'b' => 0, 'a' => .2], $result->getColor(1));
    }

    public function testColorToNativeCmyk(): void
    {
        $processor = new ColorProcessor(new CmykColorspace());
        $result = $processor->colorToNative(new CmykColor(100, 0, 0, 0));
        $this->assertInstanceOf(ImagickPixel::class, $result);
        $this->assertEquals(1.0, $result->getColorValue(Imagick::COLOR_CYAN));
        $this->assertEquals(0.0, $result->getColorValue(Imagick::COLOR_MAGENTA));
        $this->assertEquals(0.0, $result->getColorValue(Imagick::COLOR_YELLOW));
        $this->assertEquals(0.0, $result->getColorValue(Imagick::COLOR_BLACK));
    }

    public function testColorToNativeHsl(): void
    {
        $processor = new ColorProcessor(new HslColorspace());
        $result = $processor->colorToNative(new Color(255, 0, 0));
        $this->assertInstanceOf(ImagickPixel::class, $result);
    }

    public function testNativeToColor(): void
    {
        $processor = new ColorProcessor(new Colorspace());
        $result = $processor->nativeToColor(new ImagickPixel('rgb(255, 55, 0)'));
        $this->assertInstanceOf(ColorInterface::class, $result);
        $this->assertColor(255, 55, 0, 255, $result);

        $result = $processor->nativeToColor(new ImagickPixel('rgba(255, 55, 0, .2)'));
        $this->assertInstanceOf(ColorInterface::class, $result);
        $this->assertColor(255, 55, 0, 51, $result);

        $pixel = new ImagickPixel();
        $pixel->setColorValue(Imagick::COLOR_RED, 1);
        $pixel->setColorValue(Imagick::COLOR_GREEN, .3);
        $pixel->setColorValue(Imagick::COLOR_BLUE, 0);
        $pixel->setColorValue(Imagick::COLOR_ALPHA, .2);
        $result = $processor->nativeToColor($pixel);
        $this->assertInstanceOf(ColorInterface::class, $result);
        $this->assertColor(255, 77, 0, 51, $result);
    }

    public function testNativeToColorCmyk(): void
    {
        $processor = new ColorProcessor(new CmykColorspace());
        $pixel = new ImagickPixel();
        $pixel->setColorValue(Imagick::COLOR_CYAN, 1.0);
        $pixel->setColorValue(Imagick::COLOR_MAGENTA, 0.0);
        $pixel->setColorValue(Imagick::COLOR_YELLOW, 0.0);
        $pixel->setColorValue(Imagick::COLOR_BLACK, 0.0);
        $result = $processor->nativeToColor($pixel);
        $this->assertInstanceOf(CmykColor::class, $result);
    }

    public function testNativeToColorHsl(): void
    {
        $processor = new ColorProcessor(new HslColorspace());
        $result = $processor->nativeToColor(new ImagickPixel('rgb(255, 0, 0)'));
        $this->assertInstanceOf(HslColor::class, $result);
    }

    public function testNativeToColorHsv(): void
    {
        $processor = new ColorProcessor(new HsvColorspace());
        $result = $processor->nativeToColor(new ImagickPixel('rgb(255, 0, 0)'));
        $this->assertInstanceOf(HsvColor::class, $result);
    }

    public function testNativeToColorOklab(): void
    {
        $processor = new ColorProcessor(new OklabColorspace());
        $result = $processor->nativeToColor(new ImagickPixel('rgb(255, 0, 0)'));
        $this->assertInstanceOf(OklabColor::class, $result);
    }

    public function testNativeToColorOklch(): void
    {
        $processor = new ColorProcessor(new OklchColorspace());
        $result = $processor->nativeToColor(new ImagickPixel('rgb(255, 0, 0)'));
        $this->assertInstanceOf(OklchColor::class, $result);
    }
}
