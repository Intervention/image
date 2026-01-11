<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick;

use Imagick;
use ImagickPixel;
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
}
