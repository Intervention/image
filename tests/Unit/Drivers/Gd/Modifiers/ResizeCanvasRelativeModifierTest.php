<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\ResizeCanvasRelativeModifier;
use Intervention\Image\Tests\GdTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Modifiers\ResizeCanvasRelativeModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\ResizeCanvasRelativeModifier::class)]
final class ResizeCanvasRelativeModifierTest extends GdTestCase
{
    public function testModify(): void
    {
        $image = $this->createTestImage(1, 1);
        $this->assertEquals(1, $image->width());
        $this->assertEquals(1, $image->height());
        $image->modify(new ResizeCanvasRelativeModifier(2, 2, 'ff0', 'center'));
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
        $image->modify(new ResizeCanvasRelativeModifier(2, 2, 'ff0', 'center'));
        $this->assertEquals(18, $image->width());
        $this->assertEquals(18, $image->height());
        $this->assertColor(255, 255, 0, 255, $image->pickColor(0, 0));
        $this->assertColor(180, 224, 0, 255, $image->pickColor(1, 1));
        $this->assertColor(180, 224, 0, 255, $image->pickColor(2, 2));
        $this->assertColor(255, 255, 0, 255, $image->pickColor(17, 17));
        $this->assertTransparency($image->pickColor(12, 1));
    }
}
