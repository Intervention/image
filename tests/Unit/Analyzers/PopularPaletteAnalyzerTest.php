<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Analyzers;

use Generator;
use Intervention\Image\Analyzers\PopularPaletteAnalyzer;
use Intervention\Image\Color;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Size;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\DriverProvider;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[CoversClass(PopularPaletteAnalyzer::class)]
final class PopularPaletteAnalyzerTest extends BaseTestCase
{
    #[DataProvider('colorCountProvider')]
    public function testAnalyze(string $filename, int $limit, ?SizeInterface $region, int $count): void
    {
        $analyzer = new PopularPaletteAnalyzer($limit, $region);

        $result = $analyzer->analyze(Resource::create($filename)->imageObject(new GdDriver()));
        $this->assertCount($count, $result);

        $result = $analyzer->analyze(Resource::create($filename)->imageObject(new ImagickDriver()));
        $this->assertCount($count, $result);
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testAnalyzeReturnsIdenticalActualColorsOnAllDrivers(DriverInterface $driver): void
    {
        // low color images must result in the exact source colors, independent of the driver
        $image = Resource::create('blocks.png')->imageObject($driver);
        $result = $image->analyze(new PopularPaletteAnalyzer());

        $this->assertCount(3, $result);
        $this->assertColor(0, 0, 255, 255, $result[0]);
        $this->assertColor(0, 255, 0, 255, $result[1]);
        $this->assertColor(255, 0, 0, 255, $result[2]);
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testAnalyzeKeepsActualColors(DriverInterface $driver): void
    {
        // image with more than 256 colors and black as most popular color
        $image = Resource::create('apple.jpg')
            ->imageObject($driver)
            ->scale(height: 50)
            ->resizeCanvas(width: 300, background: '000');

        $palette = $image->analyze(new PopularPaletteAnalyzer());

        // the palette must contain the actual color and no quantized version
        $this->assertColor(0, 0, 0, 255, $palette->first());
        $this->assertGreaterThan(256, $palette->totalCount());
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testAnalyzeTransparency(DriverInterface $driver): void
    {
        // image with black and transparent black as popular color
        $black = Color::rgb(0, 0, 0);
        $transparentBlack = Color::rgb(0, 0, 0, .25);
        $image = $driver
            ->createImage(1, 1)
            ->fill($black)
            ->resizeCanvas(width: 5, background: $transparentBlack);

        $palette = $image->analyze(new PopularPaletteAnalyzer());

        $this->assertCount(2, $palette);
        $this->assertColor(0, 0, 0, 64, $palette->first());
        $this->assertEquals(1, $palette->colorCount($black));
        $this->assertEquals(4, $palette->colorCount($transparentBlack));
        $this->assertEquals(5, $palette->totalCount());
    }

    public static function colorCountProvider(): Generator
    {
        yield ['rgb.png', 256, null, 256];
        yield ['rgb.png', 32, null, 32];
        yield ['rgb.png', 8, null, 8];

        yield ['blocks.png', 256, null, 3];
        yield ['red.gif', 32, null, 1];

        yield ['trim.png', 32, new Size(5, 5), 1];
    }
}
