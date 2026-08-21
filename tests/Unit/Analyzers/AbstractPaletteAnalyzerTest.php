<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Analyzers;

use Generator;
use Intervention\Image\Analyzers\AbstractPaletteAnalyzer;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Size;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\DriverProvider;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[CoversClass(AbstractPaletteAnalyzer::class)]
class AbstractPaletteAnalyzerTest extends BaseTestCase
{
    #[DataProvider('sizeCountProvider')]
    public function testSampleCoordinates(SizeInterface $size, ?SizeInterface $region, int $count): void
    {
        $result = $this->analyzer()->sampleCoordinates($size, $region);
        $this->assertCount($count, iterator_to_array($result));
    }

    public function testSampleCoordinatesOffsetRegion(): void
    {
        $coordinates = iterator_to_array($this->analyzer()->sampleCoordinates(
            new Size(300, 200),
            new Size(10, 10, new Point(290, 190)),
        ));

        $this->assertCount(100, $coordinates);
        foreach ($coordinates as $coordinate) {
            [$x, $y] = $coordinate;
            $this->assertLessThan(300, $x);
            $this->assertLessThan(200, $y);
        }
    }

    public function testSampleCoordinatesOffsetRegionOutOfBounds(): void
    {
        $this->expectException(InvalidArgumentException::class);

        // the region fits the image dimensions but its position places
        // it partially outside of the image
        iterator_to_array($this->analyzer()->sampleCoordinates(
            new Size(300, 200),
            new Size(10, 10, new Point(295, 195)),
        ));
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testCollectColors(DriverInterface $driver): void
    {
        $image = Resource::create('tile.png')->imageObject($driver);
        foreach ($this->analyzer()->collectColors($image) as $color) {
            $this->assertInstanceOf(ColorInterface::class, $color);
        }
    }

    public static function sizeCountProvider(): Generator
    {
        yield [new Size(30, 30), null, 900];
        yield [new Size(300, 300), null, 3600];
        yield [new Size(500, 500), null, 2500];
        yield [new Size(1000, 1000), null, 2500];
        yield [new Size(10000, 10000), null, 111556];

        yield [new Size(10000, 10000), new Size(30, 30), 900];
        yield [new Size(300, 300), new Size(300, 300), 3600];
    }

    private function analyzer(): object
    {
        return new class () extends AbstractPaletteAnalyzer
        {
            public function analyze(ImageInterface $image): mixed
            {
                return false;
            }

            public function sampleCoordinates(SizeInterface $size, ?SizeInterface $region = null): Generator
            {
                return parent::sampleCoordinates($size, $region);
            }

            public function collectColors(ImageInterface $image, ?SizeInterface $region = null): Generator
            {
                return parent::collectColors($image, $region);
            }
        };
    }
}
