<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Analyzers;

use Intervention\Image\Analyzers\DominantPaletteAnalyzer;
use Intervention\Image\Color;
use Intervention\Image\Colors\Bin;
use Intervention\Image\Colors\Histogram;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\DriverProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Tests\Resource;
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
        $this->assertInstanceOf(Histogram::class, $result);

        $hashes = array_keys($result->toArray());
        $colors = array_values(array_map(fn(Bin $bin): ColorInterface => $bin->color, $result->toArray()));
        $counts = array_values(array_map(fn(Bin $bin): int => $bin->count, $result->toArray()));

        $this->assertEquals([337, 309, 266, 112,], $counts);
        $this->assertEquals(
            [
                Color::rgb(197, 223, 249),
                Color::rgb(99, 172, 243),
                Color::rgb(243, 131, 27),
                Color::rgb(172, 153, 116),
            ],
            $colors,
        );
        $this->assertEquals(
            [
                '12d4ebfcc0279f56beb86387e2eee01f',
                'acd2464c5360c4446048fe64cf3cf98e',
                '72268790d5cb7d7c0bd5a4473373acf3',
                'f19e833a19538c984d4746468804df1d',
            ],
            $hashes,
        );
    }
}
