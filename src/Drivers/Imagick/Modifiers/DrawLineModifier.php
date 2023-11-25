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
        $drawing->setStrokeWidth($this->drawable->width());
        $drawing->setStrokeColor(
            $this->driver()->colorToNative(
                $this->backgroundColor(),
                $image->colorspace()
            )
        );

        $drawing->line(
            $this->drawable->start()->x(),
            $this->drawable->start()->y(),
            $this->drawable->end()->x(),
            $this->drawable->end()->y(),
        );

        foreach ($image as $frame) {
            $frame->native()->drawImage($drawing);
        }

        return $image;
    }
}
