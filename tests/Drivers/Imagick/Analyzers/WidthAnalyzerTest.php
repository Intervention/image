<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Analyzers;

use Intervention\Image\Analyzers\WidthAnalyzer;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

class WidthAnalyzerTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testAnalyze(): void
    {
        $image = $this->readTestImage('tile.png');
        $analyzer = new WidthAnalyzer();
        $result = $analyzer->analyze($image);
        $this->assertEquals(16, $result);
    }
}
