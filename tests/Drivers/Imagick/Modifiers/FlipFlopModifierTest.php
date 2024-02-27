<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Requires;
use Intervention\Image\Modifiers\FlipModifier;
use Intervention\Image\Modifiers\FlopModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

#[Requires('extension imagick')]
#[CoversClass(\Intervention\Image\Modifiers\FlipModifier::class)]
#[CoversClass(\Intervention\Image\Modifiers\FlopModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\FlipModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\FlopModifier::class)]
class FlipFlopModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testFlipImage(): void
    {
        $image = $this->readTestImage('tile.png');
        $this->assertEquals('b4e000', $image->pickColor(0, 0)->toHex());
        $image->modify(new FlipModifier());
        $this->assertEquals('00000000', $image->pickColor(0, 0)->toHex());
    }

    public function testFlopImage(): void
    {
        $image = $this->readTestImage('tile.png');
        $this->assertEquals('b4e000', $image->pickColor(0, 0)->toHex());
        $image->modify(new FlopModifier());
        $this->assertEquals('00000000', $image->pickColor(0, 0)->toHex());
    }
}
