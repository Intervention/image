<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractDrawModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class DrawRectangleModifier extends AbstractDrawModifier implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $image->eachFrame(function ($frame) {
            // draw background
            if ($this->rectangle()->hasBackgroundColor()) {
                imagefilledrectangle(
                    $frame->getCore(),
                    $this->position->getX(),
                    $this->position->getY(),
                    $this->position->getX() + $this->rectangle()->bottomRightPoint()->getX(),
                    $this->position->getY() + $this->rectangle()->bottomRightPoint()->getY(),
                    $this->getBackgroundColor()->toInt()
                );
            }

            if ($this->rectangle()->hasBorder()) {
                // draw border
                imagesetthickness($frame->getCore(), $this->rectangle()->getBorderSize());
                imagerectangle(
                    $frame->getCore(),
                    $this->position->getX(),
                    $this->position->getY(),
                    $this->position->getX() + $this->rectangle()->bottomRightPoint()->getX(),
                    $this->position->getY() + $this->rectangle()->bottomRightPoint()->getY(),
                    $this->getBorderColor()->toInt()
                );
            }
        });

        return $image;
    }
}
