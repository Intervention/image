<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use RuntimeException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawLineModifier as GenericDrawLineModifier;

class DrawLineModifier extends GenericDrawLineModifier implements SpecializedInterface
{
    /**
     * @throws RuntimeException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $drawing = new ImagickDraw();
        $drawing->setStrokeWidth($this->drawable->width());
        $drawing->setFillOpacity(0);
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
