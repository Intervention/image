<?php

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Modifiers\SharpenModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 */
class SharpenModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testModify(): void
    {
        $image = $this->createTestImage('trim.png');
        $this->assertEquals('60ab96', $image->pickColor(15, 14)->toHex());
        $image->modify(new SharpenModifier(10));
        $this->assertEquals('4daba7', $image->pickColor(15, 14)->toHex());
    }
}
