<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

/**
 * @property int $width
 * @property int $height
 */
class ScaleModifier extends ResizeModifier
{
    protected function getAdjustedSize(ImageInterface $image): SizeInterface
    {
        return $image->size()->scale($this->width, $this->height);
    }
}
