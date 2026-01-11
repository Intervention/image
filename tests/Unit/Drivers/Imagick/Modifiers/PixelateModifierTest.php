<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\PixelateModifier;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\PixelateModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\PixelateModifier::class)]
final class PixelateModifierTest extends ImagickTestCase
{
    public function testModify(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->colorAt(0, 0)->toHex());
        $this->assertEquals('00aef0', $image->colorAt(14, 14)->toHex());
        $image->modify(new PixelateModifier(10));
        $this->assertColor(0, 174, 240, 255, $image->colorAt(0, 0));
        $this->assertColor(107, 171, 140, 255, $image->colorAt(14, 14));
    }
}
