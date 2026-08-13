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

        $this->assertEquals([351, 247, 226, 200,], $counts);
        $this->assertEquals(
            [
                Color::rgb(229, 138, 56),
                Color::rgb(210, 229, 249),
                Color::rgb(88, 162, 231),
                Color::rgb(145, 197, 248),
            ],
            $colors,
        );
        $this->assertEquals(
            [
                '759b9ee6f5cf5e919e48d991fbb97859',
                'd8f4cbe42b13daa0974a085f052c3cab',
                '2cc2550b32218818ba5874dab2f7a4aa',
                '3c4655e39b1e5f7c88ad98e2e43b0280',
            ],
            $hashes,
        );
    }
}
