<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Direction;
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
        $direction = $this->direction === Direction::HORIZONTAL ? IMG_FLIP_HORIZONTAL : IMG_FLIP_VERTICAL;

        foreach ($image as $frame) {
            imageflip($frame->native(), $direction);
        }

        return $image;
    }
}
