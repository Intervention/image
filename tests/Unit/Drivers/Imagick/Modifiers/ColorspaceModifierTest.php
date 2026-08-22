<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Colors\Cmyk\Colorspace as Cmyk;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Drivers\Imagick\Modifiers\ColorspaceModifier as ImagickColorspaceModifier;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
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
        $this->assertEquals(3, count($image));

        foreach ($image as $key => $frame) {
            $this->assertEquals(
                Imagick::COLORSPACE_SRGB,
                $frame->native()->getImageColorspace(),
                'Frame ' . $key . ' does not start in the source colorspace.',
            );
        }

        $image->modify(new ColorspaceModifier(Cmyk::class));

        foreach ($image as $key => $frame) {
            $this->assertEquals(
                Imagick::COLORSPACE_CMYK,
                $frame->native()->getImageColorspace(),
                'Frame ' . $key . ' was not transformed to the target colorspace.',
            );
        }
    }

    public function testApplyColorspaceUnsupportedByDriver(): void
    {
        $colorspace = new class () implements ColorspaceInterface {
            /**
             * @return array<string>
             */
            public static function channels(): array
            {
                return [];
            }

            /**
             * @param array<float> $normalized
             */
            public static function colorFromNormalized(array $normalized): ColorInterface
            {
                throw new NotSupportedException('Not implemented');
            }

            public function importColor(ColorInterface $color): ColorInterface
            {
                throw new NotSupportedException('Not implemented');
            }
        };

        $image = $this->readTestImage('test.jpg');
        $this->expectException(NotSupportedException::class);

        // the generic modifier passes a colorspace instance straight through,
        // so this has to come from the driver rather than from target parsing
        $this->expectExceptionMessageMatches('/^Colorspace .+@anonymous.* is not supported by driver$/');
        $image->modify(new ColorspaceModifier($colorspace));
    }
}
