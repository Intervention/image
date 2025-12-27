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
    #[DataProviderExternal(ColorDataProvider::class, 'rgbArray')]
    #[DataProviderExternal(ColorDataProvider::class, 'rgbString')]
    #[DataProviderExternal(ColorDataProvider::class, 'rgbHex')]
    #[DataProviderExternal(ColorDataProvider::class, 'rgbColorname')]
    public function testRgb(mixed $input, array $channels): void
    {
        $this->assertEquals(
            $channels,
            array_map(fn(ColorChannelInterface $channel): int =>
            $channel->value(), Color::rgb(...$input)->channels()),
        );
    }

    #[DataProviderExternal(ColorDataProvider::class, 'cmykArray')]
    #[DataProviderExternal(ColorDataProvider::class, 'cmykString')]
    public function testCmyk(mixed $input, array $channels): void
    {
        $this->assertEquals(
            $channels,
            array_map(fn(ColorChannelInterface $channel): int =>
            $channel->value(), Color::cmyk(...$input)->channels()),
        );
    }

    #[DataProviderExternal(ColorDataProvider::class, 'hslArray')]
    #[DataProviderExternal(ColorDataProvider::class, 'hslString')]
    public function testHsl(mixed $input, array $channels): void
    {
        $this->assertEquals(
            $channels,
            array_map(fn(ColorChannelInterface $channel): int =>
            $channel->value(), Color::hsl(...$input)->channels()),
        );
    }

    #[DataProviderExternal(ColorDataProvider::class, 'hsvArray')]
    #[DataProviderExternal(ColorDataProvider::class, 'hsvString')]
    public function testHsv(mixed $input, array $channels): void
    {
        $this->assertEquals(
            $channels,
            array_map(fn(ColorChannelInterface $channel): int =>
            $channel->value(), Color::hsv(...$input)->channels()),
        );
    }

    #[DataProviderExternal(ColorDataProvider::class, 'oklabArray')]
    #[DataProviderExternal(ColorDataProvider::class, 'oklabString')]
    public function testOklabe(mixed $input, array $channels): void
    {
        $this->assertEquals(
            $channels,
            array_map(fn(ColorChannelInterface $channel): float =>
            $channel->value(), Color::oklab(...$input)->channels()),
        );
    }
}
