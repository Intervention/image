<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors;

use Intervention\Image\Colors\ColorExtractor;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\PaletteInterface;
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
        $this->assertColor(96, 223, 223, 255, $result[0]);
        $this->assertColor(96, 159, 223, 255, $result[1]);
        $this->assertColor(96, 159, 96, 255, $result[2]);
        $this->assertColor(96, 96, 32, 255, $result[3]);
        $this->assertColor(96, 159, 159, 255, $result[4]);
        $this->assertColor(32, 96, 32, 255, $result[5]);
        $this->assertColor(96, 159, 32, 255, $result[6]);
        $this->assertColor(96, 223, 159, 255, $result[7]);
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
        $this->assertInstanceOf(PaletteInterface::class, $result);
        $this->assertCount(6, $result);
        $this->assertColor(217, 37, 38, 255, $result->vibrant());
        $this->assertColor(98, 160, 123, 255, $result->muted());
        $this->assertColor(131, 21, 20, 255, $result->darkVibrant());
        $this->assertColor(96, 51, 56, 255, $result->darkMuted());
        $this->assertColor(233, 166, 140, 255, $result->lightVibrant());
        $this->assertColor(186, 171, 164, 255, $result->lightMuted());
    }
}
