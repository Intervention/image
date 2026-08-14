<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Analyzers;

use Generator;
use Intervention\Image\Analyzers\QuantizedPaletteAnalyzer;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Size;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(QuantizedPaletteAnalyzer::class)]
final class QuantizedPaletteAnalyzerTest extends BaseTestCase
{
    #[DataProvider('colorCountProvider')]
    public function testAnalyze(string $filename, int $limit, ?SizeInterface $region, int $count): void
    {
        $analyzer = new QuantizedPaletteAnalyzer($limit, $region);

        $result = $analyzer->analyze(Resource::create($filename)->imageObject(new GdDriver()));
        $this->assertCount($count, $result);

        $result = $analyzer->analyze(Resource::create($filename)->imageObject(new ImagickDriver()));
        $this->assertCount($count, $result);
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
