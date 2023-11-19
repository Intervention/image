<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\DrawModifier;
use Intervention\Image\Interfaces\ImageInterface;

class DrawEllipseModifier extends DrawModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $background_color = $this->driver()->colorToNative(
            $this->backgroundColor(),
            $image->colorspace()
        );

        $border_color = $this->driver()->colorToNative(
            $this->borderColor(),
            $image->colorspace()
        );

        foreach ($image as $frame) {
            $drawing = new ImagickDraw();
            $drawing->setFillColor($background_color);

            if ($this->drawable->hasBorder()) {
                $drawing->setStrokeWidth($this->drawable->borderSize());
                $drawing->setStrokeColor($border_color);
            }

            $drawing->ellipse(
                $this->position()->x(),
                $this->position()->y(),
                $this->drawable->width() / 2,
                $this->drawable->height() / 2,
                0,
                360
            );

            $frame->data()->drawImage($drawing);
        }

        return $image;
    }
}
