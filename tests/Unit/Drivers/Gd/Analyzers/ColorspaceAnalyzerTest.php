<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Analyzers;

use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Drivers\Gd\Analyzers\ColorspaceAnalyzer;
use Intervention\Image\Drivers\Gd\Driver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\GdTestCase;
use Intervention\Image\Tests\Providers\ResourceProvider;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[RequiresPhpExtension('gd')]
#[CoversClass(ColorspaceAnalyzer::class)]
final class ColorspaceAnalyzerTest extends GdTestCase
{
    #[DataProviderExternal(ResourceProvider::class, 'resourceData')]
    public function testAnalyze(Resource $resource): void
    {
        $driver = new Driver();
        $analyzer = new ColorspaceAnalyzer();
        $analyzer->setDriver($driver);
        $result = $analyzer->analyze($resource->imageObject($driver));
        $this->assertInstanceOf(Colorspace::class, $result);
    }
}
