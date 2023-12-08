<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

/**
 * @property int $width
 * @property int $height
 */
class ScaleDownModifier extends ResizeModifier
{
    protected function getAdjustedSize(ImageInterface $image): SizeInterface
    {
        return $image->size()->scaleDown($this->width, $this->height);
    }
}
