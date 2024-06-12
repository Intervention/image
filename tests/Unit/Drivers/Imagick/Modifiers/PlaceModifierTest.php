<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\PlaceModifier;
use Intervention\Image\Tests\ImagickTestCase;
use Intervention\Image\Drivers\Imagick\Modifiers\PlaceModifier as PlaceModifierImagick;

#[RequiresPhpExtension('imagick')]
#[CoversClass(PlaceModifier::class)]
#[CoversClass(PlaceModifierImagick::class)]
final class PlaceModifierTest extends ImagickTestCase
{
    public function testColorChange(): void
    {
        $image = $this->readTestImage('test.jpg');
        $this->assertEquals('febc44', $image->pickColor(300, 25)->toHex());
        $image->modify(new PlaceModifier($this->getTestResourcePath('circle.png'), 'top-right', 0, 0));
        $this->assertEquals('33260e', $image->pickColor(300, 25)->toHex());
    }

    public function testColorChangeOpacityPng(): void
    {
        $image = $this->readTestImage('test.jpg');
        $this->assertEquals('febc44', $image->pickColor(300, 25)->toHex());
        $image->modify(new PlaceModifier($this->getTestResourcePath('circle.png'), 'top-right', 0, 0, 50));
        $this->assertColor(152, 112, 40, 255, $image->pickColor(300, 25), tolerance: 1);
        $this->assertColor(255, 202, 107, 255, $image->pickColor(274, 5), tolerance: 1);
    }

    public function testColorChangeOpacityJpeg(): void
    {
        $image = $this->createTestImage(16, 16)->fill('0000ff');
        $this->assertEquals('0000ff', $image->pickColor(10, 10)->toHex());
        $image->modify(new PlaceModifier($this->getTestResourcePath('exif.jpg'), opacity: 50));
        $this->assertColor(127, 83, 127, 255, $image->pickColor(10, 10), tolerance: 1);
    }
}
