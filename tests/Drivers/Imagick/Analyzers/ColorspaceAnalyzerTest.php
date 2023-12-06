<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Analyzers;

use Intervention\Image\Analyzers\ColorspaceAnalyzer;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

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
