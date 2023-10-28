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
        foreach ($image as $frame) {
            imageresolution($frame->core(), $this->x, $this->y);
        }

        return $image;
    }
}
