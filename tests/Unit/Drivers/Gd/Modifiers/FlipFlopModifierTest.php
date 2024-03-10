<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\FlipModifier;
use Intervention\Image\Modifiers\FlopModifier;
use Intervention\Image\Tests\GdTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Modifiers\FlipModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\FlipModifier::class)]
final class FlipFlopModifierTest extends GdTestCase
{
    public function testFlipImage(): void
    {
        $image = $this->readTestImage('tile.png');
        $this->assertEquals('b4e000', $image->pickColor(0, 0)->toHex());
        $image->modify(new FlipModifier());
        $this->assertTransparency($image->pickColor(0, 0));
    }

    public function testFlopImage(): void
    {
        $image = $this->readTestImage('tile.png');
        $this->assertEquals('b4e000', $image->pickColor(0, 0)->toHex());
        $image->modify(new FlopModifier());
        $this->assertTransparency($image->pickColor(0, 0));
    }
}
