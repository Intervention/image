<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Analyzers;

use Intervention\Image\Analyzers\DominantPaletteAnalyzer;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\Resource;

#[RequiresPhpExtension('gd')]
#[CoversClass(DominantPaletteAnalyzer::class)]
final class DominantPaletteAnalyzerTest extends BaseTestCase
{
    public function testAnalyze(): void
    {
        $image = Resource::create('sphere.webp')->imageObject(new Driver());
        $analyzer = new DominantPaletteAnalyzer(4);
        $result = $analyzer->analyze($image);
        $this->assertEquals(
            ['197,223,249,1', '99,172,243,1', '243,131,27,1', '172,153,116,1',],
            array_map(fn(ColorInterface $color): string => implode(',', $color->channels()), $result),
        );
    }
}
