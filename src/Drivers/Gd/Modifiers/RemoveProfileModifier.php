<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\RemoveProfileModifier as GenericRemoveProfileModifier;

class RemoveProfileModifier extends GenericRemoveProfileModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        // color profiles are not supported by GD, so the
        // image is already without profiles anyway.
        return $image;
    }
}
