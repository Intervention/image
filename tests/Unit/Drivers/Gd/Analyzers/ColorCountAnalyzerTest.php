<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Analyzers;

use Generator;
use Intervention\Image\Drivers\Gd\Analyzers\ColorCountAnalyzer;
use Intervention\Image\Drivers\Gd\Driver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\GdTestCase;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProvider;

#[RequiresPhpExtension('gd')]
#[CoversClass(ColorCountAnalyzer::class)]
final class ColorCountAnalyzerTest extends GdTestCase
{
    #[DataProvider('colorCountProvider')]
    public function testAnalyze(string $path, ?int $count): void
    {
        $analyzer = new ColorCountAnalyzer();
        $analyzer->setDriver(new Driver());
        $result = $analyzer->analyze(Resource::create($path)->imageObject(new Driver()));
        $this->assertEquals($count, $result);
    }

    public static function colorCountProvider(): Generator
    {
        yield ['red.gif', 1];
        yield ['cats.gif', 155];
        yield ['300dpi.png', null];
    }
}
