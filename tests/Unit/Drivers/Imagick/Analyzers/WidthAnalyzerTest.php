<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Analyzers;

use Intervention\Image\Drivers\Imagick\Analyzers\WidthAnalyzer;
use Intervention\Image\Drivers\Imagick\Driver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(WidthAnalyzer::class)]
final class WidthAnalyzerTest extends ImagickTestCase
{
    public function testAnalyze(): void
    {
        $image = $this->readTestImage('tile.png');
        $analyzer = new WidthAnalyzer();
        $analyzer->setDriver(new Driver());
        $result = $analyzer->analyze($image);
        $this->assertEquals(16, $result);
    }
}
