<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Modifiers\ColorizeModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Modifiers\ColorizeModifier
 * @covers \Intervention\Image\Drivers\Gd\Modifiers\ColorizeModifier
 */
class ColorizeModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testModify(): void
    {
        $image = $this->readTestImage('tile.png');
        $image = $image->modify(new ColorizeModifier(100, -100, -100));
        $this->assertColor(255, 0, 0, 255, $image->pickColor(5, 5));
        $this->assertColor(255, 0, 0, 255, $image->pickColor(15, 15));
    }
}
