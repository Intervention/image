<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\SizeInterface;

/**
 * @property int $width
 * @property int $height
 */
class FitDownModifier extends FitModifier
{
    public function getResizeSize(SizeInterface $size): SizeInterface
    {
        return $size->scaleDown($this->width, $this->height);
    }
}
