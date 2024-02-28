<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Analyzers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Analyzers\ResolutionAnalyzer;
use Intervention\Image\Resolution;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Analyzers\ResolutionAnalyzer::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Analyzers\ResolutionAnalyzer::class)]
final class ResolutionAnalyzerTest extends BaseTestCase
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
