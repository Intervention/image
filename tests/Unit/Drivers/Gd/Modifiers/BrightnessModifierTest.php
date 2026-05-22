<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\BrightnessModifier;
use Intervention\Image\Tests\GdTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Modifiers\BrightnessModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\BrightnessModifier::class)]
final class BrightnessModifierTest extends GdTestCase
{
    public function testApply(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->colorAt(14, 14)->toHex());
        $image->modify(new BrightnessModifier(30));
        $this->assertEquals('4cfaff', $image->colorAt(14, 14)->toHex());
    }

    public function testApplyOutOfRangeLevelMaxBrightness(): void
    {
        // level > 100 produces intval(level * 2.55) > 255, which GD rejects,
        // causing imagefilter() to return false and the modifier to throw.
        // Out-of-range levels should be clamped, not rejected.
        $image = $this->readTestImage('trim.png');
        $image->modify(new BrightnessModifier(200));
        $this->assertEquals('ffffff', $image->colorAt(14, 14)->toHex());
    }

    public function testApplyOutOfRangeLevelMinBrightness(): void
    {
        // level < -100 produces intval(level * 2.55) < -255, same problem.
        $image = $this->readTestImage('trim.png');
        $image->modify(new BrightnessModifier(-200));
        $this->assertEquals('000000', $image->colorAt(14, 14)->toHex());
    }
}
