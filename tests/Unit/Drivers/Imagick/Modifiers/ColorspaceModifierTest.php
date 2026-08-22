<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Colors\Cmyk\Colorspace as Cmyk;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Drivers\Imagick\Modifiers\ColorspaceModifier as ImagickColorspaceModifier;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Modifiers\ColorspaceModifier;
use Intervention\Image\Tests\ImagickTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('imagick')]
#[CoversClass(ColorspaceModifier::class)]
#[CoversClass(ImagickColorspaceModifier::class)]
final class ColorspaceModifierTest extends ImagickTestCase
{
    public function testApply(): void
    {
        $image = $this->readTestImage('test.jpg');
        $this->assertInstanceOf(Rgb::class, $image->colorspace());
        $image->modify(new ColorspaceModifier(Cmyk::class));
        $this->assertInstanceOf(Cmyk::class, $image->colorspace());
    }

    public function testApplyAnimated(): void
    {
        $image = $this->createTestAnimation();
        $image->modify(new ColorspaceModifier(Cmyk::class));

        foreach ($image as $key => $frame) {
            $this->assertEquals(
                Imagick::COLORSPACE_CMYK,
                $frame->native()->getImageColorspace(),
                'Frame ' . $key . ' was not transformed to the target colorspace.',
            );
        }
    }

    public function testApplyUnsupportedColorspace(): void
    {
        $image = $this->readTestImage('test.jpg');
        $this->expectException(NotSupportedException::class);
        $image->modify(new ColorspaceModifier('not_existing'));
    }
}
