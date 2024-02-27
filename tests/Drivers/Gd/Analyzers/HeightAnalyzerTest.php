<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Analyzers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Requires;
use Intervention\Image\Analyzers\HeightAnalyzer;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

#[Requires('extension gd')]
#[CoversClass(\Intervention\Image\Analyzers\HeightAnalyzer::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Analyzers\HeightAnalyzer::class)]
final class HeightAnalyzerTest extends TestCase
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
