<?php

namespace Intervention\Image\Tests\Drivers\Gd\Analyzers;

use Intervention\Image\Analyzers\HeightAnalyzer;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

class HeightAnalyzerTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testAnalyze(): void
    {
        $image = $this->readTestImage('tile.png');
        $analyzer = new HeightAnalyzer();
        $result = $analyzer->analyze($image);
        $this->assertEquals(16, $result);
    }
}
