<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Analyzers;

use Intervention\Image\Drivers\Gd\Analyzers\HeightAnalyzer;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\SizeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\GdTestCase;
use Intervention\Image\Tests\Providers\Gd\ResourceProvider;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[RequiresPhpExtension('gd')]
#[CoversClass(HeightAnalyzer::class)]
final class HeightAnalyzerTest extends GdTestCase
{
    #[DataProviderExternal(ResourceProvider::class, 'sizeData')]
    public function testAnalyze(DriverInterface $driver, Resource $resource, SizeInterface $size): void
    {
        $analyzer = new HeightAnalyzer();
        $analyzer->setDriver($driver);
        $result = $analyzer->analyze($resource->imageObject($driver));
        $this->assertEquals($size->height(), $result);
    }
}
