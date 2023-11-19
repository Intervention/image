<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\DrawModifier;
use Intervention\Image\Interfaces\ImageInterface;

class DrawLineModifier extends DrawModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $drawing = new ImagickDraw();
        $drawing->setStrokeWidth($this->drawable->getWidth());
        $drawing->setStrokeColor(
            $this->driver()->colorToNative(
                $this->backgroundColor(),
                $image->colorspace()
            )
        );

        $drawing->line(
            $this->drawable->getStart()->x(),
            $this->drawable->getStart()->y(),
            $this->drawable->getEnd()->x(),
            $this->drawable->getEnd()->y(),
        );

        foreach ($image as $frame) {
            $frame->data()->drawImage($drawing);
        }

        return $image;
    }
}
