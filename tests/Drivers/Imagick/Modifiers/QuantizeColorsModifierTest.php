<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Exceptions\InputException;
use Intervention\Image\Modifiers\QuantizeColorsModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\QuantizeColorsModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\QuantizeColorsModifier::class)]
final class QuantizeColorsModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

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
}
