<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

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
        $position = $this->drawable->position();

        foreach ($image as $frame) {
            // draw background
            if ($this->drawable->hasBackgroundColor()) {
                imagealphablending($frame->native(), true);
                imagesetthickness($frame->native(), 0);
                imagefilledrectangle(
                    $frame->native(),
                    $position->x(),
                    $position->y(),
                    $position->x() + $this->drawable->width(),
                    $position->y() + $this->drawable->height(),
                    $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                        $this->backgroundColor()
                    )
                );
            }

            // draw border
            if ($this->drawable->hasBorder()) {
                imagealphablending($frame->native(), true);
                imagesetthickness($frame->native(), $this->drawable->borderSize());
                imagerectangle(
                    $frame->native(),
                    $position->x(),
                    $position->y(),
                    $position->x() + $this->drawable->width(),
                    $position->y() + $this->drawable->height(),
                    $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                        $this->borderColor()
                    )
                );
            }
        }

        return $image;
    }
}
