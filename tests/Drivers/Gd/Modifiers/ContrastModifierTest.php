<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Modifiers\ContrastModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Modifiers\ContrastModifier
 * @covers \Intervention\Image\Drivers\Gd\Modifiers\ContrastModifier
 */
class ContrastModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testApply(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $image->modify(new ContrastModifier(30));
        $this->assertEquals('00ceff', $image->pickColor(14, 14)->toHex());
    }
}
