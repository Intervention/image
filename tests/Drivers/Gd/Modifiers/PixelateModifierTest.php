<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Modifiers\PixelateModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Modifiers\PixelateModifier
 * @covers \Intervention\Image\Drivers\Gd\Modifiers\PixelateModifier
 */
class PixelateModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testModify(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(0, 0)->toHex());
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $image->modify(new PixelateModifier(10));
        $this->assertEquals('00aef0', $image->pickColor(0, 0)->toHex());
        $this->assertEquals('6aaa8b', $image->pickColor(14, 14)->toHex());
    }
}
