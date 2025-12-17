<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface ModifierInterface
{
    /**
     * Apply modifications of the current modifier to the given image
     */
    public function apply(ImageInterface $image): ImageInterface;
}
