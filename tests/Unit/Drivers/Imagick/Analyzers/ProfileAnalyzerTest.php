<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Analyzers;

use Intervention\Image\Drivers\Imagick\Analyzers\ProfileAnalyzer;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Exceptions\AnalyzerException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(ProfileAnalyzer::class)]
final class ProfileAnalyzerTest extends ImagickTestCase
{
    public function testAnalyze(): void
    {
        $image = $this->readTestImage('tile.png');
        $analyzer = new ProfileAnalyzer();
        $analyzer->setDriver(new Driver());
        $this->expectException(AnalyzerException::class);
        $analyzer->analyze($image);
    }
}
