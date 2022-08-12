<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Traits\CanHandleInput;

class DrawPixelModifier implements ModifierInterface
{
    use CanHandleInput;

    public function __construct(
        protected Point $position,
        protected $color
    ) {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $color = $this->handleInput($this->color);
        return $image->eachFrame(function ($frame) use ($color) {
            imagesetpixel(
                $frame->getCore(),
                $this->position->getX(),
                $this->position->getY(),
                $color->toInt()
            );
        });
    }
}
