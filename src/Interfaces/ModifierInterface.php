<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\RuntimeException;

interface ModifierInterface
{
    /**
     * Apply modifications of the current modifier to the given image
     *
     * @throws RuntimeException
     */
    public function apply(ImageInterface $image): ImageInterface;
}
