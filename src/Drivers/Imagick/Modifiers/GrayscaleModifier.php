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
                // read the colorspace of the frame to be able to restore it,
                // getImageColorspace() only reports the frame the iterator is
                // currently pointing at
                $colorspace = $frame->native()->getImageColorspace();

                // turn image to grayscale
                $result = $frame->native()->transformImageColorspace(Imagick::COLORSPACE_GRAY);
                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to transform image to grayscale',
                    );
                }

                // return to the colorspace of the source, grayscale is a color
                // operation and is not meant to move the image to another one
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
