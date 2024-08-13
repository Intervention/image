<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\ResizeCanvasModifier;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\ResizeCanvasModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\ResizeCanvasModifier::class)]
final class ResizeCanvasModifierTest extends ImagickTestCase
{
    public function testModify(): void
    {
        $image = $this->createTestImage(1, 1);
        $this->assertEquals(1, $image->width());
        $this->assertEquals(1, $image->height());
        $image->modify(new ResizeCanvasModifier(3, 3, 'ff0', 'center'));
        $this->assertEquals(3, $image->width());
        $this->assertEquals(3, $image->height());
        $this->assertColor(255, 255, 0, 255, $image->pickColor(0, 0));
        $this->assertColor(255, 0, 0, 255, $image->pickColor(1, 1));
        $this->assertColor(255, 255, 0, 255, $image->pickColor(2, 2));
    }

    public function testModifyWithTransparency(): void
    {
        $image = $this->readTestImage('tile.png');
        $this->assertEquals(16, $image->width());
        $this->assertEquals(16, $image->height());
        $image->modify(new ResizeCanvasModifier(18, 18, 'ff0', 'center'));
        $this->assertEquals(18, $image->width());
        $this->assertEquals(18, $image->height());
        $this->assertColor(255, 255, 0, 255, $image->pickColor(0, 0));
        $this->assertColor(180, 224, 0, 255, $image->pickColor(1, 1));
        $this->assertColor(180, 224, 0, 255, $image->pickColor(2, 2));
        $this->assertColor(255, 255, 0, 255, $image->pickColor(17, 17));
        $this->assertTransparency($image->pickColor(12, 1));

        $image = $this->createTestImage(16, 16)->fill('f00');
        $image->modify(new ResizeCanvasModifier(32, 32, '00f5', 'center'));
        $this->assertEquals(32, $image->width());
        $this->assertEquals(32, $image->height());
        $this->assertColor(0, 0, 255, 77, $image->pickColor(5, 5));
        $this->assertColor(0, 0, 255, 77, $image->pickColor(16, 5));
        $this->assertColor(0, 0, 255, 77, $image->pickColor(30, 5));
        $this->assertColor(0, 0, 255, 77, $image->pickColor(5, 16));
        $this->assertColor(255, 0, 0, 255, $image->pickColor(16, 16));
        $this->assertColor(0, 0, 255, 77, $image->pickColor(30, 16));
        $this->assertColor(0, 0, 255, 77, $image->pickColor(5, 30));
        $this->assertColor(0, 0, 255, 77, $image->pickColor(16, 30));
        $this->assertColor(0, 0, 255, 77, $image->pickColor(30, 30));
    }

    public function testModifyEdge(): void
    {
        $image = $this->createTestImage(1, 1);
        $this->assertColor(255, 0, 0, 255, $image->pickColor(0, 0));
        $image->modify(new ResizeCanvasModifier(null, 2, 'ff0', 'bottom'));
        $this->assertEquals(1, $image->width());
        $this->assertEquals(2, $image->height());
        $this->assertColor(255, 255, 0, 255, $image->pickColor(0, 0));
        $this->assertColor(255, 0, 0, 255, $image->pickColor(0, 1));
    }
}
