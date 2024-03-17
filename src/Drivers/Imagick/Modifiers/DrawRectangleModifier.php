<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use RuntimeException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawRectangleModifier as GenericDrawRectangleModifier;

class DrawRectangleModifier extends GenericDrawRectangleModifier implements SpecializedInterface
{
    /**
     * @throws RuntimeException
     */
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
            $this->drawable->position()->x(),
            $this->drawable->position()->y(),
            $this->drawable->position()->x() + $this->drawable->width(),
            $this->drawable->position()->y() + $this->drawable->height()
        );

        foreach ($image as $frame) {
            $frame->native()->drawImage($drawing);
        }

        return $image;
    }
}
