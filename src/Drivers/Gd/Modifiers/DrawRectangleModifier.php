<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractDrawModifier;
use Intervention\Image\Drivers\Gd\Traits\CanHandleColors;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class DrawRectangleModifier extends AbstractDrawModifier implements ModifierInterface
{
    use CanHandleColors;

    public function apply(ImageInterface $image): ImageInterface
    {
        $image->mapFrames(function ($frame) {
            // draw background
            if ($this->rectangle()->hasBackgroundColor()) {
                imagefilledrectangle(
                    $frame->core(),
                    $this->position->x(),
                    $this->position->y(),
                    $this->position->x() + $this->rectangle()->bottomRightPoint()->x(),
                    $this->position->y() + $this->rectangle()->bottomRightPoint()->y(),
                    $this->colorToInteger($this->getBackgroundColor())
                );
            }

            if ($this->rectangle()->hasBorder()) {
                // draw border
                imagesetthickness($frame->core(), $this->rectangle()->getBorderSize());
                imagerectangle(
                    $frame->core(),
                    $this->position->x(),
                    $this->position->y(),
                    $this->position->x() + $this->rectangle()->bottomRightPoint()->x(),
                    $this->position->y() + $this->rectangle()->bottomRightPoint()->y(),
                    $this->colorToInteger($this->getBorderColor())
                );
            }
        });

        return $image;
    }
}
