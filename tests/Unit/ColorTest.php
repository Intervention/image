<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Color;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\ColorDataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;

class ColorTest extends BaseTestCase
{
    /**
     * @param $channels array<int>
     */
    #[DataProviderExternal(ColorDataProvider::class, 'rgbArray')]
    #[DataProviderExternal(ColorDataProvider::class, 'rgbString')]
    #[DataProviderExternal(ColorDataProvider::class, 'rgbHex')]
    #[DataProviderExternal(ColorDataProvider::class, 'rgbColorname')]
    public function testRgb(mixed $input, array $channels): void
    {
        $this->assertEquals(
            $channels,
            array_map(fn(ColorChannelInterface $channel): int|float =>
            $channel->value(), Color::rgb(...$input)->channels()),
        );
    }

    /**
     * @param $channels array<int>
     */
    #[DataProviderExternal(ColorDataProvider::class, 'cmykArray')]
    #[DataProviderExternal(ColorDataProvider::class, 'cmykString')]
    public function testCmyk(mixed $input, array $channels): void
    {
        $this->assertEquals(
            $channels,
            array_map(fn(ColorChannelInterface $channel): int|float =>
            $channel->value(), Color::cmyk(...$input)->channels()),
        );
    }

    /**
     * @param $channels array<int>
     */
    #[DataProviderExternal(ColorDataProvider::class, 'hslArray')]
    #[DataProviderExternal(ColorDataProvider::class, 'hslString')]
    public function testHsl(mixed $input, array $channels): void
    {
        $this->assertEquals(
            $channels,
            array_map(fn(ColorChannelInterface $channel): int|float =>
            $channel->value(), Color::hsl(...$input)->channels()),
        );
    }

    /**
     * @param $channels array<int>
     */
    #[DataProviderExternal(ColorDataProvider::class, 'hsvArray')]
    #[DataProviderExternal(ColorDataProvider::class, 'hsvString')]
    public function testHsv(mixed $input, array $channels): void
    {
        $this->assertEquals(
            $channels,
            array_map(fn(ColorChannelInterface $channel): int|float =>
            $channel->value(), Color::hsv(...$input)->channels()),
        );
    }

    /**
     * @param $channels array<float>
     */
    #[DataProviderExternal(ColorDataProvider::class, 'oklabArray')]
    #[DataProviderExternal(ColorDataProvider::class, 'oklabString')]
    public function testOklab(mixed $input, array $channels): void
    {
        $this->assertEquals(
            $channels,
            array_map(fn(ColorChannelInterface $channel): int|float =>
            $channel->value(), Color::oklab(...$input)->channels()),
        );
    }

    /**
     * @param $channels array<float>
     */
    #[DataProviderExternal(ColorDataProvider::class, 'oklchArray')]
    #[DataProviderExternal(ColorDataProvider::class, 'oklchString')]
    public function testOklch(mixed $input, array $channels): void
    {
        $this->assertEquals(
            $channels,
            array_map(fn(ColorChannelInterface $channel): int|float =>
            $channel->value(), Color::oklch(...$input)->channels()),
        );
    }
}
