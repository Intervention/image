<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Analyzers;

use Generator;
use Intervention\Image\Analyzers\QuantizedPaletteAnalyzer;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProvider;

#[RequiresPhpExtension('gd')]
#[CoversClass(QuantizedPaletteAnalyzer::class)]
final class QuantizedPaletteAnalyzerTest extends BaseTestCase
{
    #[DataProvider('colorCountProvider')]
    public function testAnalyze(string $filename, ?int $level, ?int $count): void
    {
        $image = Resource::create($filename)->imageObject(new Driver());
        $analyzer = new QuantizedPaletteAnalyzer($level);
        $result = $analyzer->analyze($image);
        $this->assertCount($count, $result);
    }

    public static function colorCountProvider(): Generator
    {
        yield ['rgb.png', null, 682];
        yield ['rgb.png', 32, 1261];
        yield ['rgb.png', 8, 182];

        yield ['blocks.png', null, 3];
        yield ['red.gif', null, 1];
    }
}
