<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Modifiers\CoverDownModifier;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\GdTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Modifiers\CoverModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\CoverModifier::class)]
final class CoverDownModifierTest extends GdTestCase
{
    public function testModify(): void
    {
        $image = $this->readTestImage('blocks.png');
        $this->assertEquals(640, $image->width());
        $this->assertEquals(480, $image->height());
        $image->modify(new CoverDownModifier(100, 100, 'center'));
        $this->assertEquals(100, $image->width());
        $this->assertEquals(100, $image->height());
        $this->assertColor(255, 0, 0, 255, $image->pickColor(90, 90));
        $this->assertColor(0, 255, 0, 255, $image->pickColor(65, 70));
        $this->assertColor(0, 0, 255, 255, $image->pickColor(70, 52));
        $this->assertTransparency($image->pickColor(90, 30));
    }

    public function testModifyOddSize(): void
    {
        $image = $this->createTestImage(375, 250);
        $image->modify(new CoverDownModifier(240, 90, 'center'));
        $this->assertEquals(240, $image->width());
        $this->assertEquals(90, $image->height());
    }
}
