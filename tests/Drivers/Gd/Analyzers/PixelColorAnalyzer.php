<?php

namespace Intervention\Image\Tests\Drivers\Gd\Analyzers;

use Intervention\Image\Analyzers\PixelColorAnalyzer;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

class PixelColorAnalyzerTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testAnalyze(): void
    {
        $image = $this->readTestImage('tile.png');
        $analyzer = new PixelColorAnalyzer(0, 0);
        $result = $analyzer->analyze($image);
        $this->assertInstanceOf(ColorInterface::class, $result);
        $this->assertEquals('b4e000', $result->toHex());
    }
}
