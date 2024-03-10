<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\ContainModifier;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\ContainModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\ContainModifier::class)]
final class ContainModifierTest extends ImagickTestCase
{
    public function testModify(): void
    {
        $image = $this->readTestImage('blocks.png');
        $this->assertEquals(640, $image->width());
        $this->assertEquals(480, $image->height());
        $result = $image->modify(new ContainModifier(200, 100, 'ff0'));
        $this->assertEquals(200, $image->width());
        $this->assertEquals(100, $image->height());
        $this->assertColor(255, 255, 0, 255, $image->pickColor(0, 0));
        $this->assertColor(0, 0, 0, 0, $image->pickColor(140, 10));
        $this->assertColor(255, 255, 0, 255, $image->pickColor(175, 10));
        $this->assertEquals(200, $result->width());
        $this->assertEquals(100, $result->height());
        $this->assertColor(255, 255, 0, 255, $result->pickColor(0, 0));
        $this->assertColor(0, 0, 0, 0, $result->pickColor(140, 10));
        $this->assertColor(255, 255, 0, 255, $result->pickColor(175, 10));
    }
}
