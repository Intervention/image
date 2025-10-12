<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Analyzers;

use Intervention\Image\Drivers\Gd\Analyzers\ResolutionAnalyzer;
use Intervention\Image\Drivers\Gd\Driver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Resolution;
use Intervention\Image\Tests\GdTestCase;
use Intervention\Image\Tests\Providers\ResourceProvider;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[RequiresPhpExtension('gd')]
#[CoversClass(ResolutionAnalyzer::class)]
final class ResolutionAnalyzerTest extends GdTestCase
{
    #[DataProviderExternal(ResourceProvider::class, 'resolutionData')]
    public function testAnalyze(Resource $resource, Resolution $resolution): void
    {
        $driver = new Driver();
        $analyzer = new ResolutionAnalyzer();
        $analyzer->setDriver($driver);
        $result = $analyzer->analyze($resource->imageObject($driver));
        $this->assertInstanceOf(Resolution::class, $result);
        $this->assertEquals($resolution->perInch()->x(), $result->perInch()->x());
        $this->assertEquals($resolution->perInch()->y(), $result->perInch()->y());
    }
}
