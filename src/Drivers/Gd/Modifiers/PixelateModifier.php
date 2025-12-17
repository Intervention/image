<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\PixelateModifier as GenericPixelateModifier;

class PixelateModifier extends GenericPixelateModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $result = imagefilter($frame->native(), IMG_FILTER_PIXELATE, $this->size, true);
            if ($result === false) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to process pixelation effect',
                );
            }
        }

        return $image;
    }
}
