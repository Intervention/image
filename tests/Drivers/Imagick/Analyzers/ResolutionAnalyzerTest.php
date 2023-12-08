<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Analyzers;

use Intervention\Image\Analyzers\ResolutionAnalyzer;
use Intervention\Image\Resolution;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

class ResolutionAnalyzerTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testAnalyze(): void
    {
        $image = $this->readTestImage('tile.png');
        $analyzer = new ResolutionAnalyzer();
        $result = $analyzer->analyze($image);
        $this->assertInstanceOf(Resolution::class, $result);
    }
}
