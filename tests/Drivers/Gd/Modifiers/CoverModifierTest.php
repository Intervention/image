<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Modifiers\CoverModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Modifiers\CoverModifier
 * @covers \Intervention\Image\Drivers\Gd\Modifiers\CoverModifier
 */
class CoverModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testModify(): void
    {
        $image = $this->readTestImage('blocks.png');
        $this->assertEquals(640, $image->width());
        $this->assertEquals(480, $image->height());
        $image->modify(new CoverModifier(100, 100, 'center'));
        $this->assertEquals(100, $image->width());
        $this->assertEquals(100, $image->height());
        $this->assertColor(255, 0, 0, 255, $image->pickColor(90, 90));
        $this->assertColor(0, 255, 0, 255, $image->pickColor(65, 70));
        $this->assertColor(0, 0, 255, 255, $image->pickColor(70, 52));
        $this->assertTransparency($image->pickColor(90, 30));
    }
}
