<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DrawModifier;
use Intervention\Image\Interfaces\ImageInterface;

class DrawRectangleModifier extends DrawModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            // draw background
            if ($this->drawable->hasBackgroundColor()) {
                imagefilledrectangle(
                    $frame->data(),
                    $this->position->x(),
                    $this->position->y(),
                    $this->position->x() + $this->drawable->width(),
                    $this->position->y() + $this->drawable->height(),
                    $this->driver()->colorToNative(
                        $this->backgroundColor(),
                        $image->colorspace()
                    )
                );
            }

            // draw border
            if ($this->drawable->hasBorder()) {
                imagesetthickness($frame->data(), $this->drawable->borderSize());
                imagerectangle(
                    $frame->data(),
                    $this->position->x(),
                    $this->position->y(),
                    $this->position->x() + $this->drawable->width(),
                    $this->position->y() + $this->drawable->height(),
                    $this->driver()->colorToNative(
                        $this->borderColor(),
                        $image->colorspace()
                    )
                );
            }
        }

        return $image;
    }
}
