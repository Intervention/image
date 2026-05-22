<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Analyzers;

use Intervention\Image\Drivers\Gd\Analyzers\ResolutionAnalyzer;
use Intervention\Image\Interfaces\DriverInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Resolution;
use Intervention\Image\Tests\GdTestCase;
use Intervention\Image\Tests\Providers\Gd\ResourceProvider;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[RequiresPhpExtension('gd')]
#[CoversClass(ResolutionAnalyzer::class)]
final class ResolutionAnalyzerTest extends GdTestCase
{
    #[DataProviderExternal(ResourceProvider::class, 'resolutionData')]
    public function testAnalyze(DriverInterface $driver, Resource $resource, Resolution $resolution): void
    {
        $analyzer = new ResolutionAnalyzer();
        $analyzer->setDriver($driver);
        $result = $analyzer->analyze($resource->imageObject($driver));
        $this->assertInstanceOf(Resolution::class, $result);
        $this->assertEquals($resolution->perInch()->x(), $result->perInch()->x(), $resource->filename());
        $this->assertEquals($resolution->perInch()->y(), $result->perInch()->y(), $resource->filename());
    }

    public function testAnalyzePngPhysUnitZeroReturnsRawRatio(): void
    {
        // A PNG with pHYs unit=0 means "unknown unit" — the x/y values are a
        // pixel aspect ratio, not pixels-per-metre. The .0254 multiplier must
        // NOT be applied; the raw values should be returned as-is.
        $driver = new \Intervention\Image\Drivers\Gd\Driver();
        $analyzer = new ResolutionAnalyzer();
        $analyzer->setDriver($driver);

        // unit0.phys.png has pHYs x=72, y=72, unit=0
        $resource = new Resource('unit0.phys.png');
        $result = $analyzer->analyze($resource->imageObject($driver));

        // With the bug the multiplier is applied: round(72 * 0.0254) = 2
        // The correct result is 72 (raw ratio returned unchanged)
        $this->assertEquals(72, $result->perInch()->x());
        $this->assertEquals(72, $result->perInch()->y());
    }
}
