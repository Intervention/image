<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\ResizeModifier;
use Intervention\Image\Tests\GdTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Modifiers\ResizeModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\ResizeModifier::class)]
final class ResizeModifierTest extends GdTestCase
{
    public function testModify(): void
    {
        $image = $this->readTestImage('blocks.png');
        $this->assertEquals(640, $image->width());
        $this->assertEquals(480, $image->height());
        $image->modify(new ResizeModifier(200, 100));
        $this->assertEquals(200, $image->width());
        $this->assertEquals(100, $image->height());
        $this->assertColor(255, 0, 0, 255, $image->pickColor(150, 70));
        $this->assertColor(0, 255, 0, 255, $image->pickColor(125, 70));
        $this->assertColor(0, 0, 255, 255, $image->pickColor(130, 54));
        $this->assertTransparency($image->pickColor(170, 30));
    }
}
