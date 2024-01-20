<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use Intervention\Image\Modifiers\PlaceModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Modifiers\PlaceModifier
 * @covers \Intervention\Image\Drivers\Gd\Modifiers\PlaceModifier
 */
class PlaceModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testColorChange(): void
    {
        $image = $this->readTestImage('test.jpg');
        $this->assertEquals('febc44', $image->pickColor(300, 25)->toHex());
        $image->modify(new PlaceModifier(__DIR__ . '/../../../images/circle.png', 'top-right', 0, 0));
        $this->assertEquals('32250d', $image->pickColor(300, 25)->toHex());
    }

    public function testColorChangeOpacity(): void
    {
        $image = $this->readTestImage('test.jpg');
        $this->assertEquals('febc44', $image->pickColor(300, 25)->toHex());
        $image->modify(new PlaceModifier(__DIR__ . '/../../../images/circle.png', 'top-right', 0, 0, 50));
        $this->assertEquals('987028', $image->pickColor(300, 25)->toHex());
    }
}
