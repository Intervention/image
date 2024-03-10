<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Analyzers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Analyzers\ProfileAnalyzer;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Analyzers\ProfileAnalyzer::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Analyzers\ProfileAnalyzer::class)]
final class ProfileAnalyzerTest extends ImagickTestCase
{
    public function testAnalyze(): void
    {
        $image = $this->readTestImage('tile.png');
        $analyzer = new ProfileAnalyzer();
        $this->expectException(ColorException::class);
        $analyzer->analyze($image);
    }
}
