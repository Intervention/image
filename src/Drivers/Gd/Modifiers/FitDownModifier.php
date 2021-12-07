<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\SizeInterface;

class FitDownModifier extends FitModifier
{
    protected function getResizeSize(SizeInterface $size): SizeInterface
    {
        return $size->scaleDown($this->width, $this->height);
    }
}
