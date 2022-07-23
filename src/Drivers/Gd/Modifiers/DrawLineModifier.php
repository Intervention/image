<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractDrawModifier;
use Intervention\Image\Exceptions\GeometryException;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class DrawLineModifier extends AbstractDrawModifier implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        return $image->eachFrame(function ($frame) {
            imageline(
                $frame->getCore(),
                $this->drawable()->getStart()->getX(),
                $this->drawable()->getStart()->getY(),
                $this->drawable()->getEnd()->getX(),
                $this->drawable()->getEnd()->getY(),
                $this->getBackgroundColor()->toInt()
            );
        });
    }

    public function drawable(): DrawableInterface
    {
        $drawable = parent::drawable();
        if (!is_a($drawable, Line::class)) {
            throw new GeometryException(
                'Shape mismatch. Excepted Line::class, found ' . get_class($drawable)
            );
        }

        return $drawable;
    }
}
