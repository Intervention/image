<?php

namespace Intervention\Image\Tests;

use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Interfaces\ColorInterface;
use Mockery\Adapter\Phpunit\MockeryTestCase;

abstract class TestCase extends MockeryTestCase
{
    public function getTestImagePath($filename = 'test.jpg'): string
    {
        return sprintf('%s/images/%s', __DIR__, $filename);
    }

    public function getTestImageData($filename = 'test.jpg'): string
    {
        return file_get_contents($this->getTestImagePath($filename));
    }

    protected function assertColor($r, $g, $b, $a, ColorInterface $color)
    {
        $this->assertEquals([$r, $g, $b, $a], $color->toArray());
    }

    protected function assertTransparency(ColorInterface $color)
    {
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(0, $color->convertTo(Colorspace::class)->alpha()->value());
    }
}
