<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\AbstractDrawModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Geometry\Line;

/**
 * @method ColorInterface backgroundColor()
 * @property Line $drawable
 */
class DrawLineModifier extends AbstractDrawModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            imagealphablending($frame->native(), true);
            imageantialias($frame->native(), true);
            imagesetthickness($frame->native(), $this->drawable->width());
            imageline(
                $frame->native(),
                $this->drawable->start()->x(),
                $this->drawable->start()->y(),
                $this->drawable->end()->x(),
                $this->drawable->end()->y(),
                $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                    $this->backgroundColor()
                )
            );
        }

        return $image;
    }
}
