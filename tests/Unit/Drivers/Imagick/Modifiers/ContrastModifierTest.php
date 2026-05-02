<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Image;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\ContrastModifier;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\ContrastModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\ContrastModifier::class)]
final class ContrastModifierTest extends ImagickTestCase
{
    public function testApply(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->colorAt(14, 14)->toHex());
        $image->modify(new ContrastModifier(30));
        $this->assertEquals('00cffc', $image->colorAt(14, 14)->toHex());
    }

    public function testApplyPreservesMidtone(): void
    {
        // sigmoidalContrastImage takes its midpoint argument in QuantumRange
        // units. Passing 0 pivots the curve around pure black, lifting every
        // pixel — including the midtone, which a contrast adjustment must keep
        // fixed. After the fix the midpoint is QUANTUM_RANGE / 2 and a mid-grey
        // pixel survives a contrast adjustment unchanged.
        $image = $this->createMidGreyImage();
        $this->assertColor(128, 128, 128, 255, $image->colorAt(0, 0));
        $image->modify(new ContrastModifier(30));
        $this->assertColor(128, 128, 128, 255, $image->colorAt(0, 0), tolerance: 1);
    }

    private function createMidGreyImage(): Image
    {
        $imagick = new Imagick();
        $imagick->newImage(1, 1, new ImagickPixel('rgb(128, 128, 128)'), 'png');
        $imagick->setImageType(Imagick::IMGTYPE_TRUECOLOR);
        $imagick->setColorspace(Imagick::COLORSPACE_SRGB);

        return new Image(new Driver(), new Core($imagick));
    }
}
