<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Generator;
use Intervention\Image\Analyzers\AbstractPaletteAnalyzer;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Size;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\DriverProvider;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;

class AbstractPaletteAnalyzerTest extends BaseTestCase
{
    #[DataProvider('sizeCountProvider')]
    public function testSampleCoordinates(SizeInterface $size, int $count): void
    {
        $result = $this->analyzer()->sampleCoordinates($size);
        $this->assertCount($count, iterator_to_array($result));
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
        yield [new Size(30, 30), 900];
        yield [new Size(300, 300), 3600];
        yield [new Size(500, 500), 2500];
        yield [new Size(1000, 1000), 2500];
        yield [new Size(10000, 10000), 111556];
    }

    private function analyzer(): object
    {
        return new class () extends AbstractPaletteAnalyzer
        {
            public function analyze(ImageInterface $image): mixed
            {
                return false;
            }

            public function sampleCoordinates(SizeInterface $size): Generator
            {
                return parent::sampleCoordinates($size);
            }

            public function collectColors(ImageInterface $image): Generator
            {
                return parent::collectColors($image);
            }
        };
    }
}
