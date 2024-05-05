<?php

declare(strict_types=1);

namespace Intervention\Image\Tests;

use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Interfaces\ColorInterface;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PHPUnit\Framework\ExpectationFailedException;

abstract class BaseTestCase extends MockeryTestCase
{
    public function getTestResourcePath($filename = 'test.jpg'): string
    {
        return sprintf('%s/resources/%s', __DIR__, $filename);
    }

    public function getTestResourceData($filename = 'test.jpg'): string
    {
        return file_get_contents($this->getTestResourcePath($filename));
    }

    public function getTestResourcePointer($filename = 'test.jpg')
    {
        $pointer = fopen('php://temp', 'rw');
        fputs($pointer, $this->getTestResourceData($filename));
        rewind($pointer);

        return $pointer;
    }

    /**
     * Assert that given color equals the given color channel values in the given optional tolerance
     *
     * @param int $r
     * @param int $g
     * @param int $b
     * @param int $a
     * @param ColorInterface $color
     * @param int $tolerance
     * @throws ExpectationFailedException
     * @return void
     */
    protected function assertColor(int $r, int $g, int $b, int $a, ColorInterface $color, int $tolerance = 0)
    {
        $this->assertContains(
            $color->channel(Red::class)->value(),
            range(max($r - $tolerance, 0), min($r + $tolerance, 255)),
            'Failed asserting that color ' .
                $color->convertTo(Colorspace::class)->toString() .
                ' equals '
                . $color->convertTo(Colorspace::class)->toString()
        );

        $this->assertContains(
            $color->channel(Green::class)->value(),
            range(max($g - $tolerance, 0), min($g + $tolerance, 255)),
            'Failed asserting that color ' .
                $color->convertTo(Colorspace::class)->toString() .
                ' equals '
                . $color->convertTo(Colorspace::class)->toString()
        );

        $this->assertContains(
            $color->channel(Blue::class)->value(),
            range(max($b - $tolerance, 0), min($b + $tolerance, 255)),
            'Failed asserting that color ' .
                $color->convertTo(Colorspace::class)->toString() .
                ' equals '
                . $color->convertTo(Colorspace::class)->toString()
        );

        $this->assertContains(
            $color->channel(Alpha::class)->value(),
            range(max($a - $tolerance, 0), min($a + $tolerance, 255)),
            'Failed asserting that color ' .
                $color->convertTo(Colorspace::class)->toString() .
                ' equals '
                . $color->convertTo(Colorspace::class)->toString()
        );
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
