<?php

declare(strict_types=1);

namespace Intervention\Image\Tests;

use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\ColorInterface;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PHPUnit\Framework\ExpectationFailedException;

abstract class BaseTestCase extends MockeryTestCase
{
    public static function getTestResourcePath($filename = 'test.jpg'): string
    {
        return sprintf('%s/resources/%s', __DIR__, $filename);
    }

    public static function getTestResourceData($filename = 'test.jpg'): string
    {
        return file_get_contents(self::getTestResourcePath($filename));
    }

    public static function getTestResourcePointer($filename = 'test.jpg')
    {
        $pointer = fopen('php://temp', 'rw');
        fputs($pointer, self::getTestResourceData($filename));
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
        // build errorMessage
        $errorMessage = function (int $r, int $g, $b, int $a, ColorInterface $color): string {
            $color = 'rgba(' . implode(', ', [
                $color->channel(Red::class)->value(),
                $color->channel(Green::class)->value(),
                $color->channel(Blue::class)->value(),
                $color->channel(Alpha::class)->value(),
            ]) . ')';

            return implode(' ', [
                'Failed asserting that color',
                $color,
                'equals',
                'rgba(' . $r . ', ' . $g . ', ' . $b . ', ' . $a . ')'
            ]);
        };

        // build color channel value range
        $range = function (int $base, int $tolerance): array {
            return range(max($base - $tolerance, 0), min($base + $tolerance, 255));
        };

        $this->assertContains(
            $color->channel(Red::class)->value(),
            $range($r, $tolerance),
            $errorMessage($r, $g, $b, $a, $color)
        );

        $this->assertContains(
            $color->channel(Green::class)->value(),
            $range($g, $tolerance),
            $errorMessage($r, $g, $b, $a, $color)
        );

        $this->assertContains(
            $color->channel(Blue::class)->value(),
            $range($b, $tolerance),
            $errorMessage($r, $g, $b, $a, $color)
        );

        $this->assertContains(
            $color->channel(Alpha::class)->value(),
            $range($a, $tolerance),
            $errorMessage($r, $g, $b, $a, $color)
        );
    }

    protected function assertTransparency(ColorInterface $color)
    {
        $this->assertInstanceOf(RgbColor::class, $color);
        $channel = $color->channel(Alpha::class);
        $this->assertEquals(0, $channel->value());
    }

    protected function assertMediaType(string|array $allowed, string|EncodedImage $input): void
    {
        $pointer = fopen('php://temp', 'rw');
        fputs($pointer, (string) $input);
        rewind($pointer);
        $detected = mime_content_type($pointer);
        fclose($pointer);

        $allowed = is_string($allowed) ? [$allowed] : $allowed;
        $this->assertTrue(in_array($detected, $allowed));
    }

    protected function assertMediaTypeBitmap(string|EncodedImage $input): void
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
