<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
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
        $drawing = new ImagickDraw();
        $drawing->setStrokeColor($this->getBackgroundColor()->getPixel());
        $drawing->setStrokeWidth($this->drawable()->getWidth());
        $drawing->line(
            $this->drawable()->getStart()->getX(),
            $this->drawable()->getStart()->getY(),
            $this->drawable()->getEnd()->getX(),
            $this->drawable()->getEnd()->getY(),
        );
        return $image->eachFrame(function ($frame) use ($drawing) {
            $frame->getCore()->drawImage($drawing);
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
