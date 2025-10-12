<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Analyzers;

use Intervention\Image\Drivers\Gd\Analyzers\WidthAnalyzer;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Interfaces\SizeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\Providers\ResourceProvider;
use Intervention\Image\Tests\Resource;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[RequiresPhpExtension('gd')]
#[CoversClass(WidthAnalyzer::class)]
final class WidthAnalyzerTest extends MockeryTestCase
{
    #[DataProviderExternal(ResourceProvider::class, 'sizeData')]
    public function testAnalyze(Resource $resource, SizeInterface $size): void
    {
        $driver = new Driver();
        $analyzer = new WidthAnalyzer();
        $analyzer->setDriver($driver);
        $result = $analyzer->analyze($resource->imageObject($driver));
        $this->assertEquals($size->width(), $result);
    }
}
