<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\FlipModifier as GenericFlipModifier;

class FlipModifier extends GenericFlipModifier implements SpecializedInterface
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
            $result = imageflip($frame->native(), IMG_FLIP_VERTICAL);
            if ($result === false) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to mirror image',
                );
            }
        }

        return $image;
    }
}
