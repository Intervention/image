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
                $colorspace = $frame->native()->getImageColorspace();

                // turn frame to grayscale
                $result = $frame->native()->transformImageColorspace(Imagick::COLORSPACE_GRAY);
                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to transform image to grayscale',
                    );
                }

                // return frame to the original colorspace: grayscale is only a color operation.
                $result = $frame->native()->transformImageColorspace($colorspace);
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
