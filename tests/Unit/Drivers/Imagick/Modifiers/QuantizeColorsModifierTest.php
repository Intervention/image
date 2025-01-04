<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Exceptions\InputException;
use Intervention\Image\Modifiers\QuantizeColorsModifier;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\QuantizeColorsModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\QuantizeColorsModifier::class)]
final class QuantizeColorsModifierTest extends ImagickTestCase
{
    public function testColorChange(): void
    {
        $image = $this->readTestImage('gradient.bmp');
        $this->assertEquals(15, $image->core()->native()->getImageColors());
        $image->modify(new QuantizeColorsModifier(4));
        $this->assertEquals(4, $image->core()->native()->getImageColors());
    }

    public function testNoColorReduction(): void
    {
        $image = $this->readTestImage('gradient.bmp');
        $this->assertEquals(15, $image->core()->native()->getImageColors());
        $image->modify(new QuantizeColorsModifier(150));
        $this->assertEquals(15, $image->core()->native()->getImageColors());
    }

    public function testInvalidColorInput(): void
    {
        $image = $this->readTestImage('gradient.bmp');
        $this->expectException(InputException::class);
        $image->modify(new QuantizeColorsModifier(0));
    }

    public function testVerifyColorValueAfterQuantization(): void
    {
        $image = $this->createTestImage(3, 2)->fill('f00');
        $image->modify(new QuantizeColorsModifier(1));
        $this->assertColor(255, 0, 0, 255, $image->pickColor(1, 1));
    }
}
