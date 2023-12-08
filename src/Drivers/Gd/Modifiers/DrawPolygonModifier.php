<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\AbstractDrawModifier;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ColorInterface;

/**
 * @method Point position()
 * @method ColorInterface backgroundColor()
 * @method ColorInterface borderColor()
 * @property Polygon $drawable
 */
class DrawPolygonModifier extends AbstractDrawModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            if ($this->drawable->hasBackgroundColor()) {
                imagefilledpolygon(
                    $frame->native(),
                    $this->drawable->toArray(),
                    $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                        $this->backgroundColor()
                    )
                );
            }

            if ($this->drawable->hasBorder()) {
                imagesetthickness($frame->native(), $this->drawable->borderSize());
                imagepolygon(
                    $frame->native(),
                    $this->drawable->toArray(),
                    $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                        $this->borderColor()
                    )
                );
            }
        }

        return $image;
    }
}
