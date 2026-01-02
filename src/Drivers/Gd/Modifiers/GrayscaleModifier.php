<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\GrayscaleModifier as GenericGrayscaleModifier;

class GrayscaleModifier extends GenericGrayscaleModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws ModifierException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $result = imagefilter($frame->native(), IMG_FILTER_GRAYSCALE);
            if ($result === false) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to transform image to grayscale',
                );
            }
        }

        return $image;
    }
}
