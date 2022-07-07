<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class DrawPixelModifier implements ModifierInterface
{
    public function __construct(protected Point $position, protected ColorInterface $color)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            imagesetpixel(
                $frame->getCore(),
                $this->position->getX(),
                $this->position->getY(),
                $this->color->toInt()
            );
        }

        return $image;
    }
}
