<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\GrayscaleModifier as GenericGrayscaleModifier;

class GrayscaleModifier extends GenericGrayscaleModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            try {
                // turn image to grayscale
                $result = $frame->native()->transformImageColorspace(Imagick::COLORSPACE_GRAY);
                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to transform image to grayscale',
                    );
                }

                // return to srgb colorspace with grayscale image
                $result = $frame->native()->transformImageColorspace(Imagick::COLORSPACE_SRGB);
                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to transform image to grayscale',
                    );
                }
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to transform image to grayscale',
                    previous: $e,
                );
            }
        }

        return $image;
    }
}
