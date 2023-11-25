<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Analyzers;

use Intervention\Image\Analyzers\ProfileAnalyzer;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

class ProfleAnalyzerTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testAnalyze(): void
    {
        $image = $this->createTestImage('tile.png');
        $analyzer = new ProfileAnalyzer();
        $this->expectException(ColorException::class);
        $analyzer->analyze($image);
    }
}
