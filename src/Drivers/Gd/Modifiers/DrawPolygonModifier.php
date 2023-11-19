<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DrawModifier;
use Intervention\Image\Interfaces\ImageInterface;

class DrawPolygonModifier extends DrawModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            if ($this->drawable->hasBackgroundColor()) {
                imagefilledpolygon(
                    $frame->data(),
                    $this->drawable->toArray(),
                    $this->driver()->colorToNative(
                        $this->backgroundColor(),
                        $image->colorspace()
                    )
                );
            }

            if ($this->drawable->hasBorder()) {
                imagesetthickness($frame->data(), $this->drawable->borderSize());
                imagepolygon(
                    $frame->data(),
                    $this->drawable->toArray(),
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
