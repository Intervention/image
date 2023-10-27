<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Traits\CanHandleColors;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Traits\CanHandleInput;

class DrawPixelModifier implements ModifierInterface
{
    use CanHandleInput;
    use CanHandleColors;

    public function __construct(
        protected Point $position,
        protected $color
    ) {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $color = $this->handleInput($this->color);
        return $image->mapFrames(function ($frame) use ($color) {
            imagesetpixel(
                $frame->core(),
                $this->position->x(),
                $this->position->y(),
                $this->colorToInteger($color)
            );
        });
    }
}
