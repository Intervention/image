<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Analyzers;

use Intervention\Image\Analyzers\ResolutionAnalyzer;
use Intervention\Image\Resolution;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Analyzers\ResolutionAnalyzer
 * @covers \Intervention\Image\Drivers\Imagick\Analyzers\ResolutionAnalyzer
 */
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
