<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors;

use Intervention\Image\Analyzers\PopularPaletteAnalyzer;
use Intervention\Image\Colors\ColorExtractor;
use Intervention\Image\Colors\Swatches\Filters\VibrantMutedFilter;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use Intervention\Image\Interfaces\SwatchesInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\DriverProvider;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProviderExternal;

class ColorExtractorTest extends BaseTestCase
{
    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testPopular(DriverInterface $driver): void
    {
        $image = Resource::create('apple.jpg')->imageObject($driver);
        $extractor = new ColorExtractor($image);
        $result = $extractor->popular(8);
        $this->assertInstanceOf(PaletteInterface::class, $result);
        $this->assertCount(8, $result);
        $this->assertColor(99, 197, 206, 255, $result[0]);
        $this->assertColor(88, 196, 208, 255, $result[1]);
        $this->assertColor(104, 202, 211, 255, $result[2]);
        $this->assertColor(97, 194, 203, 255, $result[3]);
        $this->assertColor(73, 186, 200, 255, $result[4]);
        $this->assertColor(103, 204, 214, 255, $result[5]);
        $this->assertColor(88, 189, 199, 255, $result[6]);
        $this->assertColor(93, 191, 200, 255, $result[7]);
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testPopularSortedByPresence(DriverInterface $driver): void
    {
        $image = Resource::create('apple.jpg')->imageObject($driver);
        $result = (new ColorExtractor($image))->popular(8);

        $counts = array_map(fn(ColorInterface $color): int => $result->colorCount($color), $result->toArray());
        $sorted = $counts;
        rsort($sorted);
        $this->assertEquals($sorted, $counts);
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testDominant(DriverInterface $driver): void
    {
        $image = Resource::create('apple.jpg')->imageObject($driver);
        $extractor = new ColorExtractor($image);
        $result = $extractor->dominant(4);
        $this->assertInstanceOf(PaletteInterface::class, $result);
        $this->assertCount(4, $result);
        $this->assertColor(95, 196, 205, 255, $result[0]);
        $this->assertColor(223, 163, 144, 255, $result[1]);
        $this->assertColor(163, 47, 42, 255, $result[2]);
        $this->assertColor(93, 122, 67, 255, $result[3]);
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testSwatches(DriverInterface $driver): void
    {
        $image = Resource::create('apple.jpg')->imageObject($driver);
        $extractor = new ColorExtractor($image);
        $result = $extractor->swatches();
        $this->assertInstanceOf(SwatchesInterface::class, $result);
        $this->assertCount(6, $result);
        $this->assertColor(217, 37, 38, 255, $result->vibrant);
        $this->assertColor(98, 160, 123, 255, $result->muted);
        $this->assertColor(128, 19, 25, 255, $result->darkVibrant);
        $this->assertColor(96, 51, 56, 255, $result->darkMuted);
        $this->assertColor(233, 166, 140, 255, $result->lightVibrant);
        $this->assertColor(186, 171, 164, 255, $result->lightMuted);
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testSwatchesIndependentOfPaletteOrder(DriverInterface $driver): void
    {
        $image = Resource::create('apple.jpg')->imageObject($driver);
        $palette = $image->analyze(new PopularPaletteAnalyzer(256));

        $swatches = (new VibrantMutedFilter())->filterColors($palette);
        $reordered = (new VibrantMutedFilter())->filterColors($palette->sortByPresence());

        $this->assertEquals($swatches->toArray(), $reordered->toArray());
    }
}
