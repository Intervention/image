<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Analyzers;

use Intervention\Image\Analyzers\DominantPaletteAnalyzer;
use Intervention\Image\Color;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use Intervention\Image\Size;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\DriverProvider;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[CoversClass(DominantPaletteAnalyzer::class)]
final class DominantPaletteAnalyzerTest extends BaseTestCase
{
    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testAnalyze(DriverInterface $driver): void
    {
        $image = Resource::create('sphere.webp')->imageObject($driver);
        $analyzer = new DominantPaletteAnalyzer(4);
        $result = $analyzer->analyze($image);
        $this->assertInstanceOf(PaletteInterface::class, $result);

        $counts = array_map(fn(ColorInterface $color) => $result->colorCount($color), $result->toArray());
        $this->assertEquals([351, 247, 226, 200], $counts);
        $this->assertEquals(
            [
                Color::rgb(229, 138, 56),
                Color::rgb(210, 229, 249),
                Color::rgb(88, 162, 231),
                Color::rgb(145, 197, 248),
            ],
            $result->toArray(),
        );
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testAnalyzeRepeatedCallsAreDeterministic(DriverInterface $driver): void
    {
        $image = Resource::create('sphere.webp')->imageObject($driver);
        $analyzer = new DominantPaletteAnalyzer(4);

        $this->assertEquals(
            $analyzer->analyze($image)->toArray(),
            $analyzer->analyze($image)->toArray(),
        );
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testAnalyzeRegion(DriverInterface $driver): void
    {
        $image = Resource::create('trim.png')->imageObject($driver);
        $analyzer = new DominantPaletteAnalyzer(4, new Size(5, 5));
        $result = $analyzer->analyze($image);
        $this->assertInstanceOf(PaletteInterface::class, $result);

        $counts = array_map(fn(ColorInterface $color) => $result->colorCount($color), $result->toArray());
        $this->assertEquals([25], $counts);
        $this->assertEquals(
            [
                Color::rgb(0, 174, 240),
            ],
            $result->toArray(),
        );
    }
}
