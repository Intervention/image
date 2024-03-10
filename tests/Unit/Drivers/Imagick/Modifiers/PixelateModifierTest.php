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
        $this->assertEquals('00aef0', $image->pickColor(0, 0)->toHex());
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $image->modify(new PixelateModifier(10));

        list($r, $g, $b) = $image->pickColor(0, 0)->toArray();
        $this->assertEquals(0, $r);
        $this->assertEquals(174, $g);
        $this->assertEquals(240, $b);

        list($r, $g, $b) = $image->pickColor(14, 14)->toArray();
        $this->assertEquals(107, $r);
        $this->assertEquals(171, $g);
        $this->assertEquals(140, $b);
    }
}
