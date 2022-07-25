<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\Abstract\Modifiers\AbstractDrawModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class DrawLineModifier extends AbstractDrawModifier implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $drawing = new ImagickDraw();
        $drawing->setStrokeColor($this->getBackgroundColor()->getPixel());
        $drawing->setStrokeWidth($this->line()->getWidth());
        $drawing->line(
            $this->line()->getStart()->getX(),
            $this->line()->getStart()->getY(),
            $this->line()->getEnd()->getX(),
            $this->line()->getEnd()->getY(),
        );

        return $image->eachFrame(function ($frame) use ($drawing) {
            $frame->getCore()->drawImage($drawing);
        });
    }
}
