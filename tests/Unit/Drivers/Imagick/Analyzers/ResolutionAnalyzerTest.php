<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Analyzers;

use Intervention\Image\Drivers\Imagick\Analyzers\ResolutionAnalyzer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Resolution;
use Intervention\Image\Tests\ImagickTestCase;
use Intervention\Image\Tests\Providers\ResourceProvider;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[RequiresPhpExtension('imagick')]
#[CoversClass(ResolutionAnalyzer::class)]
final class ResolutionAnalyzerTest extends ImagickTestCase
{
    #[DataProviderExternal(ResourceProvider::class, 'resolutionData')]
    public function testAnalyze(Resource $resource, Resolution $resolution): void
    {
        $driver = new Driver();
        $analyzer = new ResolutionAnalyzer();
        $analyzer->setDriver($driver);
        $result = $analyzer->analyze($resource->imageObject($driver));
        $this->assertInstanceOf(Resolution::class, $result);
        $this->assertEquals($resolution->perInch()->x(), round($result->perInch()->x()));
        $this->assertEquals($resolution->perInch()->y(), round($result->perInch()->y()));
    }
}
