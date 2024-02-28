<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick;

use ImagickPixel;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Drivers\Imagick\ColorProcessor;
use Intervention\Image\Tests\BaseTestCase;

final class ColorProcessorTest extends BaseTestCase
{
    public function testColorToNative(): void
    {
        $processor = new ColorProcessor(new Colorspace());
        $result = $processor->colorToNative(new Color(255, 55, 0, 255));
        $this->assertInstanceOf(ImagickPixel::class, $result);
    }

    public function testNativeToColor(): void
    {
        $processor = new ColorProcessor(new Colorspace());
        $processor->nativeToColor(new ImagickPixel('rgb(255, 55, 0)'));
    }
}
