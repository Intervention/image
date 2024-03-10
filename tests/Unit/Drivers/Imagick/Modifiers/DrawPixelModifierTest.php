<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\DrawPixelModifier;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\DrawPixelModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\DrawPixelModifier::class)]
final class DrawPixelModifierTest extends ImagickTestCase
{
    public function testApply(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $image->modify(new DrawPixelModifier(new Point(14, 14), 'ffffff'));
        $this->assertEquals('ffffff', $image->pickColor(14, 14)->toHex());
    }
}
