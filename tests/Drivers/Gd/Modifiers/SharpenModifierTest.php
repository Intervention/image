<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Modifiers\SharpenModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Modifiers\SharpenModifier
 * @covers \Intervention\Image\Drivers\Gd\Modifiers\SharpenModifier
 */
class SharpenModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testModify(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('60ab96', $image->pickColor(15, 14)->toHex());
        $image->modify(new SharpenModifier(10));
        $this->assertEquals('4daba7', $image->pickColor(15, 14)->toHex());
    }
}
