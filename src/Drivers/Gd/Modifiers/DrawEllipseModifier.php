<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractDrawModifier;
use Intervention\Image\Drivers\Gd\Traits\CanHandleColors;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class DrawEllipseModifier extends AbstractDrawModifier implements ModifierInterface
{
    use CanHandleColors;

    public function apply(ImageInterface $image): ImageInterface
    {
        return $image->mapFrames(function ($frame) {
            if ($this->ellipse()->hasBorder()) {
                // slightly smaller ellipse to keep 1px bordered edges clean
                if ($this->ellipse()->hasBackgroundColor()) {
                    imagefilledellipse(
                        $frame->core(),
                        $this->position->x(),
                        $this->position->y(),
                        $this->ellipse()->getWidth() - 1,
                        $this->ellipse()->getHeight() - 1,
                        $this->colorToInteger($this->getBackgroundColor())
                    );
                }

                imagesetthickness($frame->core(), $this->ellipse()->getBorderSize());

                // gd's imageellipse ignores imagesetthickness so i use
                // imagearc with 360 degrees instead.
                imagearc(
                    $frame->core(),
                    $this->position->x(),
                    $this->position->y(),
                    $this->ellipse()->getWidth(),
                    $this->ellipse()->getHeight(),
                    0,
                    360,
                    $this->colorToInteger($this->getBorderColor())
                );
            } else {
                imagefilledellipse(
                    $frame->core(),
                    $this->position->x(),
                    $this->position->y(),
                    $this->ellipse()->getWidth(),
                    $this->ellipse()->getHeight(),
                    $this->colorToInteger($this->getBackgroundColor())
                );
            }
        });
    }
}
