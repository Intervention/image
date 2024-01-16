<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Modifiers\GreyscaleModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Modifiers\GreyscaleModifier
 * @covers \Intervention\Image\Drivers\Gd\Modifiers\GreyscaleModifier
 */
class GreyscaleModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testColorChange(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertFalse($image->pickColor(0, 0)->isGreyscale());
        $image->modify(new GreyscaleModifier());
        $this->assertTrue($image->pickColor(0, 0)->isGreyscale());
    }
}
