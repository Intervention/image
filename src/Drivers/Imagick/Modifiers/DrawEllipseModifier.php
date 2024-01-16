<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\AbstractDrawModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Geometry\Ellipse;

/**
 * @property Ellipse $drawable
 */
class DrawEllipseModifier extends AbstractDrawModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $background_color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->backgroundColor()
        );

        $border_color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->borderColor()
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

            $frame->native()->drawImage($drawing);
        }

        return $image;
    }
}
