<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\Abstract\Modifiers\AbstractDrawModifier;
use Intervention\Image\Drivers\Imagick\Traits\CanHandleColors;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class DrawRectangleModifier extends AbstractDrawModifier implements ModifierInterface
{
    use CanHandleColors;

    public function apply(ImageInterface $image): ImageInterface
    {
        $drawing = new ImagickDraw();
        $background_color = $this->colorToPixel($this->getBackgroundColor());
        $border_color = $this->colorToPixel($this->getBorderColor());

        $drawing->setFillColor($background_color);
        if ($this->rectangle()->hasBorder()) {
            $drawing->setStrokeColor($border_color);
            $drawing->setStrokeWidth($this->rectangle()->getBorderSize());
        }

        // build rectangle
        $drawing->rectangle(
            $this->position->getX(),
            $this->position->getY(),
            $this->position->getX() + $this->rectangle()->bottomRightPoint()->getX(),
            $this->position->getY() + $this->rectangle()->bottomRightPoint()->getY()
        );

        $image->eachFrame(function ($frame) use ($drawing) {
            $frame->getCore()->drawImage($drawing);
        });

        return $image;
    }
}
