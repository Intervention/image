<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Analyzers;

use Intervention\Image\Drivers\Gd\Analyzers\ColorspaceAnalyzer;
use Intervention\Image\Interfaces\DriverInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\GdTestCase;
use Intervention\Image\Tests\Providers\Gd\ResourceProvider;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[RequiresPhpExtension('gd')]
#[CoversClass(ColorspaceAnalyzer::class)]
final class ColorspaceAnalyzerTest extends GdTestCase
{
    #[DataProviderExternal(ResourceProvider::class, 'colorspaceData')]
    public function testAnalyze(DriverInterface $driver, Resource $resource, string $colorspace): void
    {
        $analyzer = new ColorspaceAnalyzer();
        $analyzer->setDriver($driver);
        $result = $analyzer->analyze($resource->imageObject($driver));
        $this->assertInstanceOf($colorspace, $result);
    }
}
