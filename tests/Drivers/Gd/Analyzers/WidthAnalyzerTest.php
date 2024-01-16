<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Analyzers;

use Intervention\Image\Analyzers\WidthAnalyzer;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Analyzers\WidthAnalyzer
 * @covers \Intervention\Image\Drivers\Imagick\Analyzers\WidthAnalyzer
 */
class WidthAnalyzerTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testAnalyze(): void
    {
        $image = $this->readTestImage('tile.png');
        $analyzer = new WidthAnalyzer();
        $result = $analyzer->analyze($image);
        $this->assertEquals(16, $result);
    }
}
