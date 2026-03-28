<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Analyzers;

use Intervention\Image\Drivers\Imagick\Analyzers\WidthAnalyzer;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Interfaces\SizeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\ImagickTestCase;
use Intervention\Image\Tests\Providers\ResourceProvider;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[RequiresPhpExtension('imagick')]
#[CoversClass(WidthAnalyzer::class)]
final class WidthAnalyzerTest extends ImagickTestCase
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
