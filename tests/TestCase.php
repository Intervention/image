<?php

declare(strict_types=1);

namespace Intervention\Image\Tests;

use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
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
        $this->assertInstanceOf(RgbColor::class, $color);
        $channel = $color->channel(Alpha::class);
        $this->assertEquals(0, $channel->value());
    }

    protected function assertMediaType(string|array $allowed, string $input): void
    {
        $pointer = fopen('php://temp', 'rw');
        fputs($pointer, $input);
        rewind($pointer);
        $detected = mime_content_type($pointer);
        fclose($pointer);

        $allowed = is_string($allowed) ? [$allowed] : $allowed;
        $this->assertTrue(in_array($detected, $allowed));
    }

    protected function assertMediaTypeBitmap(string $input): void
    {
        $this->assertMediaType([
            'image/x-ms-bmp',
            'image/bmp',
            'bmp',
            'ms-bmp',
            'x-bitmap',
            'x-bmp',
            'x-ms-bmp',
            'x-win-bitmap',
            'x-windows-bmp',
            'x-xbitmap',
            'image/ms-bmp',
            'image/x-bitmap',
            'image/x-bmp',
            'image/x-ms-bmp',
            'image/x-win-bitmap',
            'image/x-windows-bmp',
            'image/x-xbitmap',
        ], $input);
    }
}
