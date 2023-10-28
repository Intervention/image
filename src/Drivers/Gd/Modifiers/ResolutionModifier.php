<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class ResolutionModifier implements ModifierInterface
{
    public function __construct(protected float $x, protected float $y)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $x = intval(round($this->x));
        $y = intval(round($this->y));

        foreach ($image as $frame) {
            imageresolution($frame->core(), $x, $y);
        }

        return $image;
    }
}
