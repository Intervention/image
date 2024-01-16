<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\AbstractDrawModifier;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Geometry\Point;

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
        foreach ($image as $frame) {
            // draw background
            if ($this->drawable->hasBackgroundColor()) {
                imagealphablending($frame->native(), true);
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
                imagealphablending($frame->native(), true);
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
