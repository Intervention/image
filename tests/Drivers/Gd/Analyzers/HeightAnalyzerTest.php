<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Analyzers;

use Intervention\Image\Analyzers\HeightAnalyzer;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Analyzers\HeightAnalyzer
 * @covers \Intervention\Image\Drivers\Imagick\Analyzers\HeightAnalyzer
 */
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
