<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\AbstractDrawModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Geometry\Rectangle;

/**
 * @method Point position()
 * @method ColorInterface backgroundColor()
 * @method ColorInterface borderColor()
 * @property Rectangle $drawable
 */
class DrawRectangleModifier extends AbstractDrawModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $drawing = new ImagickDraw();

        $background_color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->backgroundColor()
        );

        $border_color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->borderColor()
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
