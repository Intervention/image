<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Modifiers\BrightnessModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Modifiers\BrightnessModifier
 * @covers \Intervention\Image\Drivers\Gd\Modifiers\BrightnessModifier
 */
class BrightnessModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testApply(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $image->modify(new BrightnessModifier(30));
        $this->assertEquals('4cfaff', $image->pickColor(14, 14)->toHex());
    }
}
