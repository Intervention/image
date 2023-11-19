<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DrawModifier;
use Intervention\Image\Interfaces\ImageInterface;

class DrawLineModifier extends DrawModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            imageline(
                $frame->data(),
                $this->drawable->getStart()->x(),
                $this->drawable->getStart()->y(),
                $this->drawable->getEnd()->x(),
                $this->drawable->getEnd()->y(),
                $this->driver()->colorToNative(
                    $this->backgroundColor(),
                    $image->colorspace()
                )
            );
        }

        return $image;
    }
}
