<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickException;
use Intervention\Image\Colors\Cmyk\Colorspace as Cmyk;
use Intervention\Image\Colors\Hsl\Colorspace as Hsl;
use Intervention\Image\Colors\Hsv\Colorspace as Hsv;
use Intervention\Image\Colors\Oklab\Colorspace as Oklab;
use Intervention\Image\Colors\Oklch\Colorspace as Oklch;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ColorspaceModifier as GenericColorspaceModifier;

class ColorspaceModifier extends GenericColorspaceModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     * @throws NotSupportedException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $colorspace = $this->targetColorspace();
        $imagick = $image->core()->native();

        try {
            $result = $imagick->transformImageColorspace(
                $this->imagickColorspaceOrFail($colorspace)
            );

            if ($result === false) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to transform image colorspace',
                );
            }
        } catch (ImagickException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to transform image colorspace',
                previous: $e
            );
        }

        return $image;
    }

    /**
     * @throws NotSupportedException
     */
    private function imagickColorspaceOrFail(ColorspaceInterface $colorspace): int
    {
        if ($colorspace instanceof Rgb) {
            return Imagick::COLORSPACE_SRGB;
        }

        if ($colorspace instanceof Cmyk) {
            return Imagick::COLORSPACE_CMYK;
        }

        if ($colorspace instanceof Hsl) {
            return Imagick::COLORSPACE_HSL;
        }

        if ($colorspace instanceof Hsv) {
            return Imagick::COLORSPACE_HSB;
        }

        if ($colorspace instanceof Oklab && defined(Imagick::class . '::COLORSPACE_OKLAB')) {
            return constant(Imagick::class . '::COLORSPACE_OKLAB');
        }

        if ($colorspace instanceof Oklch && defined(Imagick::class . '::COLORSPACE_OKLCH')) {
            return constant(Imagick::class . '::COLORSPACE_OKLCH');
        }

        throw new NotSupportedException('Colorspace ' . $colorspace::class . ' is not supported by driver');
    }
}
