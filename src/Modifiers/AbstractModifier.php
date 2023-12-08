<?php

namespace Intervention\Image\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

abstract class AbstractModifier implements ModifierInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        return $image->modify($this);
    }
}
