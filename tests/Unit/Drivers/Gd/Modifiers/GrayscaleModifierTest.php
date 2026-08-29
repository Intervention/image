<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Modifiers\GrayscaleModifier;
use Intervention\Image\Tests\GdTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Modifiers\GrayscaleModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\GrayscaleModifier::class)]
final class GrayscaleModifierTest extends GdTestCase
{
    public function testColorChange(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertFalse($image->colorAt(0, 0)->isGrayscale());
        $image->modify(new GrayscaleModifier());
        $this->assertTrue($image->colorAt(0, 0)->isGrayscale());
    }

    public function testSaturatedColorsKeepDistinctBrightness(): void
    {
        // blocks.png holds pure blue, green and red in three of its quadrants
        $image = $this->readTestImage('blocks.png')->modify(new GrayscaleModifier());

        $blue = $image->colorAt(160, 120)->channel(Red::class)->value();
        $green = $image->colorAt(160, 360)->channel(Red::class)->value();
        $red = $image->colorAt(480, 360)->channel(Red::class)->value();

        // any luma based conversion keeps blue darkest and green brightest
        $this->assertLessThan($red, $blue);
        $this->assertLessThan($green, $red);
    }
}
