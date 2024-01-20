<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Modifiers\PlaceModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Modifiers\BlurModifier
 * @covers \Intervention\Image\Drivers\Imagick\Modifiers\PlaceModifier
 */
class PlaceModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testColorChange(): void
    {
        $image = $this->readTestImage('test.jpg');
        $this->assertEquals('febc44', $image->pickColor(300, 25)->toHex());
        $image->modify(new PlaceModifier(__DIR__ . '/../../../images/circle.png', 'top-right', 0, 0));
        $this->assertEquals('33260e', $image->pickColor(300, 25)->toHex());
    }

    public function testColorChangeOpacity(): void
    {
        $image = $this->readTestImage('test.jpg');
        $this->assertEquals('febc44', $image->pickColor(300, 25)->toHex());
        $image->modify(new PlaceModifier(__DIR__ . '/../../../images/circle.png', 'top-right', 0, 0, 50));
        $this->assertEquals('987129', $image->pickColor(300, 25)->toHex());
    }
}
