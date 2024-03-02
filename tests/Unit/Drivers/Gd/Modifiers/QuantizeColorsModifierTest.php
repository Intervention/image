<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Exceptions\InputException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Modifiers\QuantizeColorsModifier;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Modifiers\QuantizeColorsModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\QuantizeColorsModifier::class)]
final class QuantizeColorsModifierTest extends BaseTestCase
{
    use CanCreateGdTestImage;

    public function testColorChange(): void
    {
        $image = $this->readTestImage('gradient.bmp');
        $this->assertColorCount(15, $image);
        $image->modify(new QuantizeColorsModifier(4));
        $this->assertColorCount(4, $image);
    }

    public function testNoColorReduction(): void
    {
        $image = $this->readTestImage('gradient.bmp');
        $this->assertColorCount(15, $image);
        $image->modify(new QuantizeColorsModifier(150));
        $this->assertColorCount(15, $image);
    }

    public function testInvalidColorInput(): void
    {
        $image = $this->readTestImage('gradient.bmp');
        $this->expectException(InputException::class);
        $image->modify(new QuantizeColorsModifier(0));
    }

    private function assertColorCount(int $count, ImageInterface $image): void
    {
        $colors = [];
        $width = $image->width();
        $height = $image->height();
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $rgb = imagecolorat($image->core()->native(), $x, $y);
                $color = imagecolorsforindex($image->core()->native(), $rgb);
                $color = implode('-', $color);
                $colors[$color] = $color;
            }
        }

        $this->assertEquals(count($colors), $count);
    }
}
