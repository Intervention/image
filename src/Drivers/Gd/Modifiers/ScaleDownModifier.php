<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class ScaleDownModifier extends ResizeModifier
{
    /**
     * {@inheritdoc}
     *
     * @see ResizeModifier::getAdjustedSize()
     */
    protected function getAdjustedSize(ImageInterface $image): SizeInterface
    {
        return $image->size()->scaleDown($this->width, $this->height);
    }
}
