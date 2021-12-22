<?php

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Modifiers\InvertModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;


/**
 * @covers \Intervention\Image\Drivers\Gd\Modifiers\InvertModifier
 */
class InvertModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testApply(): void
    {
        $image = $this->createTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(0, 0)->toHex());
        $this->assertEquals('ffa601', $image->pickColor(25, 25)->toHex());
        $image->modify(new InvertModifier());
        $this->assertEquals('ff510f', $image->pickColor(0, 0)->toHex());
        $this->assertEquals('0059fe', $image->pickColor(25, 25)->toHex());
    }
}
