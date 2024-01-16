<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class ResizeDownModifier extends ResizeModifier
{
    protected function getAdjustedSize(ImageInterface $image): SizeInterface
    {
        return $image->size()->resizeDown($this->width, $this->height);
    }
}
