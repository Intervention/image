<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\AbstractDrawModifier;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ColorInterface;

/**
 * @method ColorInterface backgroundColor()
 * @property Line $drawable
 */
class DrawLineModifier extends AbstractDrawModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $drawing = new ImagickDraw();
        $drawing->setStrokeWidth($this->drawable->width());
        $drawing->setStrokeColor(
            $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                $this->backgroundColor()
            )
        );

        $drawing->line(
            $this->drawable->start()->x(),
            $this->drawable->start()->y(),
            $this->drawable->end()->x(),
            $this->drawable->end()->y(),
        );

        foreach ($image as $frame) {
            $frame->native()->drawImage($drawing);
        }

        return $image;
    }
}
