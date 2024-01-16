<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Modifiers\BlurModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Modifiers\BlurModifier
 * @covers \Intervention\Image\Drivers\Gd\Modifiers\BlurModifier
 */
class BlurModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testColorChange(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $image->modify(new BlurModifier(30));
        $this->assertEquals('4fa68d', $image->pickColor(14, 14)->toHex());
    }
}
