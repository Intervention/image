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
    /**
     * Assert that given color equals the given color channel values in the given optional tolerance
     */
    protected function assertColor(int $r, int $g, int $b, float $a, ColorInterface $color, int $tolerance = 0): void
    {
        // build errorMessage
        $errorMessage = function (int $r, int $g, $b, float $a, ColorInterface $color): string {
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

        $this->assertThat(
            $color->channel(Red::class)->value(),
            $this->logicalAnd(
                $this->greaterThanOrEqual(max(0, $r - $tolerance)),
                $this->lessThanOrEqual(min(255, $r + $tolerance))
            ),
            message: $errorMessage($r, $g, $b, $a, $color)
        );

        $this->assertThat(
            $color->channel(Green::class)->value(),
            $this->logicalAnd(
                $this->greaterThanOrEqual(max(0, $g - $tolerance)),
                $this->lessThanOrEqual(min(255, $g + $tolerance))
            ),
            message: $errorMessage($r, $g, $b, $a, $color)
        );

        $this->assertThat(
            $color->channel(Blue::class)->value(),
            $this->logicalAnd(
                $this->greaterThanOrEqual(max(0, $b - $tolerance)),
                $this->lessThanOrEqual(min(255, $b + $tolerance))
            ),
            message: $errorMessage($r, $g, $b, $a, $color)
        );

        $this->assertThat(
            round($color->channel(Alpha::class)->value() * 255, 2),
            $this->logicalAnd(
                $this->greaterThanOrEqual(round(max(0, $a * 255 - $tolerance), 2)),
                $this->lessThanOrEqual(round(min(255, $a * 255 + $tolerance), 2))
            ),
            message: $errorMessage($r, $g, $b, $a, $color)
        );
    }

    protected function assertBetween(int|float $min, int|float $max, int|float $value): void
    {
        if ($value < $min || $value > $max) {
            throw new ExpectationFailedException(
                'Failed asserting that value ' . $value . ' is between ' . $min . ' and ' . $max,
            );
        }
    }

    protected function assertTransparency(ColorInterface $color): void
    {
        $this->assertInstanceOf(RgbColor::class, $color);
        $channel = $color->channel(Alpha::class);
        $this->assertEquals(0, $channel->value(), 'Detected color ' . $color . ' is not completely transparent.');
    }

    protected function assertMediaType(string|array $allowed, string|EncodedImage $input): void
    {
        $pointer = fopen('php://temp', 'rw');
        fwrite($pointer, (string) $input);
        rewind($pointer);
        $detected = mime_content_type($pointer);
        fclose($pointer);

        $allowed = is_string($allowed) ? [$allowed] : $allowed;
        $this->assertTrue(
            in_array($detected, $allowed),
            'Detected media type "' . $detected . '" is not: ' . implode(', ', $allowed),
        );
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
