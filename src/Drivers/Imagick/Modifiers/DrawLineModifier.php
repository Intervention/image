<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\Abstract\Modifiers\AbstractDrawModifier;
use Intervention\Image\Drivers\Imagick\Traits\CanHandleColors;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class DrawLineModifier extends AbstractDrawModifier implements ModifierInterface
{
    use CanHandleColors;

    public function apply(ImageInterface $image): ImageInterface
    {
        $drawing = new ImagickDraw();
        $drawing->setStrokeWidth($this->line()->getWidth());
        $drawing->setStrokeColor(
            $this->colorToPixel($this->getBackgroundColor(), $image->colorspace())
        );

        $drawing->line(
            $this->line()->getStart()->x(),
            $this->line()->getStart()->y(),
            $this->line()->getEnd()->x(),
            $this->line()->getEnd()->y(),
        );

        return $image->mapFrames(function ($frame) use ($drawing) {
            $frame->core()->drawImage($drawing);
        });
    }
}
