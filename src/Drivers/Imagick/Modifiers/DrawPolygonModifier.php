<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\DrawModifier;
use Intervention\Image\Interfaces\ImageInterface;

class DrawPolygonModifier extends DrawModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $drawing = new ImagickDraw();

        if ($this->drawable->hasBackgroundColor()) {
            $background_color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                $this->backgroundColor()
            );

            $drawing->setFillColor($background_color);
        }

        if ($this->drawable->hasBorder()) {
            $border_color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                $this->borderColor()
            );

            $drawing->setStrokeColor($border_color);
            $drawing->setStrokeWidth($this->drawable->borderSize());
        }

        $drawing->polygon($this->points());

        foreach ($image as $frame) {
            $frame->native()->drawImage($drawing);
        }

        return $image;
    }

    private function points(): array
    {
        $points = [];
        foreach ($this->drawable as $point) {
            $points[] = ['x' => $point->x(), 'y' => $point->y()];
        }

        return $points;
    }
}
