<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Analyzers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Analyzers\PixelColorAnalyzer;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Analyzers\PixelColorAnalyzer::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Analyzers\PixelColorAnalyzer::class)]
final class PixelColorAnalyzerTest extends ImagickTestCase
{
    public function testAnalyze(): void
    {
        $image = $this->readTestImage('tile.png');
        $analyzer = new PixelColorAnalyzer(0, 0);
        $result = $analyzer->analyze($image);
        $this->assertInstanceOf(ColorInterface::class, $result);
        $this->assertEquals('b4e000', $result->toHex());
    }
}
