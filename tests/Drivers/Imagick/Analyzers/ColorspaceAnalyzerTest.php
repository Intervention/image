<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Analyzers;

use Intervention\Image\Analyzers\ColorspaceAnalyzer;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Analyzers\ColorspaceAnalyzer
 * @covers \Intervention\Image\Drivers\Imagick\Analyzers\ColorspaceAnalyzer
 */
class ColorspaceAnalyzerTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testAnalyze(): void
    {
        $image = $this->readTestImage('tile.png');
        $analyzer = new ColorspaceAnalyzer();
        $result = $analyzer->analyze($image);
        $this->assertInstanceOf(ColorspaceInterface::class, $result);
    }
}
