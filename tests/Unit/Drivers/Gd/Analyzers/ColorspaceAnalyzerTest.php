<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Analyzers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Analyzers\ColorspaceAnalyzer;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Analyzers\ColorspaceAnalyzer::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Analyzers\ColorspaceAnalyzer::class)]
final class ColorspaceAnalyzerTest extends BaseTestCase
{
    use CanCreateGdTestImage;

    public function testAnalyze(): void
    {
        $image = $this->readTestImage('tile.png');
        $analyzer = new ColorspaceAnalyzer();
        $result = $analyzer->analyze($image);
        $this->assertInstanceOf(ColorspaceInterface::class, $result);
    }
}
