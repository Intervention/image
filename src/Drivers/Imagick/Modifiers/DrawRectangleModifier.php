<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\DrawModifier;
use Intervention\Image\Interfaces\ImageInterface;

class DrawRectangleModifier extends DrawModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $drawing = new ImagickDraw();

        $background_color = $this->driver()->colorToNative(
            $this->backgroundColor(),
            $image->colorspace(),
        );

        $border_color = $this->driver()->colorToNative(
            $this->borderColor(),
            $image->colorspace(),
        );

        $drawing->setFillColor($background_color);
        if ($this->drawable->hasBorder()) {
            $drawing->setStrokeColor($border_color);
            $drawing->setStrokeWidth($this->drawable->borderSize());
        }

        // build rectangle
        $drawing->rectangle(
            $this->position()->x(),
            $this->position()->y(),
            $this->position()->x() + $this->drawable->width(),
            $this->position()->y() + $this->drawable->height()
        );

        foreach ($image as $frame) {
            $frame->native()->drawImage($drawing);
        }

        return $image;
    }
}
