<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\Exceptions\LogicException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

abstract class SpecializableModifier extends Specializable implements ModifierInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws LogicException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        if ($this instanceof SpecializedInterface) {
            throw new LogicException(
                "Specialized class '" . static::class . "' must override apply()"
            );
        }

        return $image->modify($this);
    }
}
