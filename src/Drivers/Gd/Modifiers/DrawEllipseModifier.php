<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DrawModifier;
use Intervention\Image\Interfaces\ImageInterface;

class DrawEllipseModifier extends DrawModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            if ($this->drawable->hasBorder()) {
                // slightly smaller ellipse to keep 1px bordered edges clean
                if ($this->drawable->hasBackgroundColor()) {
                    imagefilledellipse(
                        $frame->native(),
                        $this->position()->x(),
                        $this->position()->y(),
                        $this->drawable->width() - 1,
                        $this->drawable->height() - 1,
                        $this->driver()->colorToNative(
                            $this->backgroundColor(),
                            $image->colorspace()
                        )
                    );
                }

                // gd's imageellipse ignores imagesetthickness
                // so i use imagearc with 360 degrees instead.
                imagesetthickness(
                    $frame->native(),
                    $this->drawable->borderSize(),
                );

                imagearc(
                    $frame->native(),
                    $this->position()->x(),
                    $this->position()->y(),
                    $this->drawable->width(),
                    $this->drawable->height(),
                    0,
                    360,
                    $this->driver()->colorToNative(
                        $this->borderColor(),
                        $image->colorspace()
                    )
                );
            } else {
                imagefilledellipse(
                    $frame->native(),
                    $this->position()->x(),
                    $this->position()->y(),
                    $this->drawable->width(),
                    $this->drawable->height(),
                    $this->driver()->colorToNative(
                        $this->backgroundColor(),
                        $image->colorspace()
                    )
                );
            }
        }

        return $image;
    }
}
