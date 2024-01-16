<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Analyzers;

use Intervention\Image\Analyzers\ResolutionAnalyzer;
use Intervention\Image\Resolution;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Analyzers\ResolutionAnalyzer
 * @covers \Intervention\Image\Drivers\Imagick\Analyzers\ResolutionAnalyzer
 */
class ResolutionAnalyzerTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testAnalyze(): void
    {
        $image = $this->readTestImage('tile.png');
        $analyzer = new ResolutionAnalyzer();
        $result = $analyzer->analyze($image);
        $this->assertInstanceOf(Resolution::class, $result);
    }
}
