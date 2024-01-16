<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Analyzers;

use Intervention\Image\Analyzers\ProfileAnalyzer;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Analyzers\ProfileAnalyzer
 * @covers \Intervention\Image\Drivers\Imagick\Analyzers\ProfileAnalyzer
 */
class ProfleAnalyzerTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testAnalyze(): void
    {
        $image = $this->readTestImage('tile.png');
        $analyzer = new ProfileAnalyzer();
        $this->expectException(ColorException::class);
        $analyzer->analyze($image);
    }
}
