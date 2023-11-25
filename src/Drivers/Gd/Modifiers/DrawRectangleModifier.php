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
                    $frame->native(),
                    $this->position()->x(),
                    $this->position()->y(),
                    $this->position()->x() + $this->drawable->width(),
                    $this->position()->y() + $this->drawable->height(),
                    $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                        $this->backgroundColor()
                    )
                );
            }

            // draw border
            if ($this->drawable->hasBorder()) {
                imagesetthickness($frame->native(), $this->drawable->borderSize());
                imagerectangle(
                    $frame->native(),
                    $this->position()->x(),
                    $this->position()->y(),
                    $this->position()->x() + $this->drawable->width(),
                    $this->position()->y() + $this->drawable->height(),
                    $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                        $this->borderColor()
                    )
                );
            }
        }

        return $image;
    }
}
