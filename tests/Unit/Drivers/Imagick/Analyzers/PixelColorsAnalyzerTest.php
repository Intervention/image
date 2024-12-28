<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Analyzers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Collection;
use Intervention\Image\Drivers\Imagick\Analyzers\PixelColorsAnalyzer;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(PixelColorsAnalyzer::class)]
final class PixelColorsAnalyzerTest extends ImagickTestCase
{
    public function testAnalyzeAnimated(): void
    {
        $image = $this->readTestImage('animation.gif');
        $analyzer = new PixelColorsAnalyzer(0, 0);
        $analyzer->setDriver(new Driver());
        $result = $analyzer->analyze($image);
        $this->assertInstanceOf(Collection::class, $result);
        $colors = array_map(fn(ColorInterface $color) => $color->toHex(), $result->toArray());
        $this->assertEquals($colors, ["394b63", "394b63", "394b63", "ffa601", "ffa601", "ffa601", "ffa601", "394b63"]);
    }

    public function testAnalyzeNonAnimated(): void
    {
        $image = $this->readTestImage('tile.png');
        $analyzer = new PixelColorsAnalyzer(0, 0);
        $analyzer->setDriver(new Driver());
        $result = $analyzer->analyze($image);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertInstanceOf(ColorInterface::class, $result->first());
        $this->assertEquals('b4e000', $result->first()->toHex());
    }
}
