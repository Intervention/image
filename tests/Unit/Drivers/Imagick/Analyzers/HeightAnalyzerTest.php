<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Analyzers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Analyzers\HeightAnalyzer;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Analyzers\HeightAnalyzer::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Analyzers\HeightAnalyzer::class)]
final class HeightAnalyzerTest extends BaseTestCase
{
    use CanCreateImagickTestImage;

    public function testAnalyze(): void
    {
        $image = $this->readTestImage('tile.png');
        $analyzer = new HeightAnalyzer();
        $result = $analyzer->analyze($image);
        $this->assertEquals(16, $result);
    }
}
