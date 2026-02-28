<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use Intervention\Image\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\ReduceColorsModifier;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\ReduceColorsModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\ReduceColorsModifier::class)]
final class ReduceColorsModifierTest extends ImagickTestCase
{
    public function testColorChange(): void
    {
        $image = $this->readTestImage('gradient.bmp');
        $this->assertEquals(15, $image->core()->native()->getImageColors());
        $image->modify(new ReduceColorsModifier(4));
        $this->assertEquals(4, $image->core()->native()->getImageColors());
    }

    public function testNoColorReduction(): void
    {
        $image = $this->readTestImage('gradient.bmp');
        $this->assertEquals(15, $image->core()->native()->getImageColors());
        $image->modify(new ReduceColorsModifier(150));
        $this->assertEquals(15, $image->core()->native()->getImageColors());
    }

    public function testInvalidColorInput(): void
    {
        $image = $this->readTestImage('gradient.bmp');
        $this->expectException(InvalidArgumentException::class);
        $image->modify(new ReduceColorsModifier(0));
    }

    public function testVerifyColorValueAfterQuantization(): void
    {
        $image = $this->createTestImage(3, 2)->fill('f00');
        $image->modify(new ReduceColorsModifier(1));
        $this->assertColor(255, 0, 0, 255, $image->colorAt(1, 1));
    }
}
