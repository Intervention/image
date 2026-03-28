<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Analyzers;

use Intervention\Image\Drivers\Imagick\Analyzers\ColorspaceAnalyzer;
use Intervention\Image\Drivers\Imagick\Driver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\ImagickTestCase;
use Intervention\Image\Tests\Providers\ResourceProvider;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[RequiresPhpExtension('imagick')]
#[CoversClass(ColorspaceAnalyzer::class)]
final class ColorspaceAnalyzerTest extends ImagickTestCase
{
    #[DataProviderExternal(ResourceProvider::class, 'colorspaceData')]
    public function testAnalyze(Resource $resource, string $colorspace): void
    {
        $driver = new Driver();
        $analyzer = new ColorspaceAnalyzer();
        $analyzer->setDriver($driver);
        $result = $analyzer->analyze($resource->imageObject($driver));
        $this->assertInstanceOf($colorspace, $result);
    }
}
