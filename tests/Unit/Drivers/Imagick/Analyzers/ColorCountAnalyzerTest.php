<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Analyzers;

use Generator;
use Intervention\Image\Drivers\Imagick\Analyzers\ColorCountAnalyzer;
use Intervention\Image\Drivers\Imagick\Driver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\ImagickTestCase;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProvider;

#[RequiresPhpExtension('imagick')]
#[CoversClass(ColorCountAnalyzer::class)]
final class ColorCountAnalyzerTest extends ImagickTestCase
{
    #[DataProvider('colorCountProvider')]
    public function testAnalyze(string $path, int $count): void
    {
        $analyzer = new ColorCountAnalyzer();
        $analyzer->setDriver(new Driver());
        $result = $analyzer->analyze(Resource::create($path)->imageObject(new Driver()));
        $this->assertEquals($count, $result);
    }

    public static function colorCountProvider(): Generator
    {
        yield ['red.gif', 1];
        yield ['300dpi.png', 341];
        yield ['test.jpg', 128];
    }
}
