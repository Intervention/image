<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\NotSupportedException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\TrimModifier;
use Intervention\Image\Tests\GdTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Modifiers\TrimModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\TrimModifier::class)]
final class TrimModifierTest extends GdTestCase
{
    public function testTrim(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals(50, $image->width());
        $this->assertEquals(50, $image->height());
        $image->modify(new TrimModifier());
        $this->assertEquals(28, $image->width());
        $this->assertEquals(28, $image->height());
    }

    public function testTrimGradient(): void
    {
        $image = $this->readTestImage('radial.png');
        $this->assertEquals(50, $image->width());
        $this->assertEquals(50, $image->height());
        $image->modify(new TrimModifier(50));
        $this->assertEquals(35, $image->width());
        $this->assertEquals(35, $image->height());
    }

    public function testTrimHighTolerance(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals(50, $image->width());
        $this->assertEquals(50, $image->height());
        $image->modify(new TrimModifier(1000000));
        $this->assertEquals(1, $image->width());
        $this->assertEquals(1, $image->height());
        $this->assertColor(255, 255, 255, 0, $image->pickColor(0, 0));
    }

    public function testTrimAnimated(): void
    {
        $image = $this->readTestImage('animation.gif');
        $this->expectException(NotSupportedException::class);
        $image->modify(new TrimModifier());
    }
}
