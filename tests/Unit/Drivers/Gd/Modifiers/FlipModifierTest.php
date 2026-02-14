<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use Intervention\Image\Direction;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\FlipModifier;
use Intervention\Image\Tests\GdTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Modifiers\FlipModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\FlipModifier::class)]
final class FlipModifierTest extends GdTestCase
{
    public function testFlipImageDefault(): void
    {
        $image = $this->readTestImage('tile.png');
        $this->assertEquals('b4e000', $image->colorAt(5, 5)->toHex());
        $this->assertEquals('00000000', $image->colorAt(12, 5)->toHex());
        $this->assertEquals('00000000', $image->colorAt(5, 12)->toHex());
        $this->assertEquals('445160', $image->colorAt(12, 12)->toHex());
        $image->modify(new FlipModifier());
        $this->assertEquals('00000000', $image->colorAt(5, 5)->toHex());
        $this->assertEquals('b4e000', $image->colorAt(12, 5)->toHex());
        $this->assertEquals('445160', $image->colorAt(5, 12)->toHex());
        $this->assertEquals('00000000', $image->colorAt(12, 12)->toHex());
    }

    public function testFlipImageHorizontal(): void
    {
        $image = $this->readTestImage('tile.png');
        $this->assertEquals('b4e000', $image->colorAt(5, 5)->toHex());
        $this->assertEquals('00000000', $image->colorAt(12, 5)->toHex());
        $this->assertEquals('00000000', $image->colorAt(5, 12)->toHex());
        $this->assertEquals('445160', $image->colorAt(12, 12)->toHex());
        $image->modify(new FlipModifier(Direction::HORIZONTAL));
        $this->assertEquals('00000000', $image->colorAt(5, 5)->toHex());
        $this->assertEquals('b4e000', $image->colorAt(12, 5)->toHex());
        $this->assertEquals('445160', $image->colorAt(5, 12)->toHex());
        $this->assertEquals('00000000', $image->colorAt(12, 12)->toHex());
    }

    public function testFlipImageVertical(): void
    {
        $image = $this->readTestImage('tile.png');
        $this->assertEquals('b4e000', $image->colorAt(5, 5)->toHex());
        $this->assertEquals('00000000', $image->colorAt(12, 5)->toHex());
        $this->assertEquals('00000000', $image->colorAt(5, 12)->toHex());
        $this->assertEquals('445160', $image->colorAt(12, 12)->toHex());
        $image->modify(new FlipModifier(Direction::VERTICAL));
        $this->assertEquals('00000000', $image->colorAt(5, 5)->toHex());
        $this->assertEquals('445160', $image->colorAt(12, 5)->toHex());
        $this->assertEquals('b4e000', $image->colorAt(5, 12)->toHex());
        $this->assertEquals('00000000', $image->colorAt(12, 12)->toHex());
    }
}
