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
            if ($this->drawable()->hasBackgroundColor()) {
                imagefilledrectangle(
                    $frame->getCore(),
                    $this->position->getX(),
                    $this->position->getY(),
                    $this->position->getX() + $this->drawable()->bottomRightPoint()->getX(),
                    $this->position->getY() + $this->drawable()->bottomRightPoint()->getY(),
                    $this->getBackgroundColor()->toInt()
                );
            }

            if ($this->drawable()->hasBorder()) {
                // draw border
                imagesetthickness($frame->getCore(), $this->drawable()->getBorderSize());
                imagerectangle(
                    $frame->getCore(),
                    $this->position->getX(),
                    $this->position->getY(),
                    $this->position->getX() + $this->drawable()->bottomRightPoint()->getX(),
                    $this->position->getY() + $this->drawable()->bottomRightPoint()->getY(),
                    $this->getBorderColor()->toInt()
                );
            }
        });

        return $image;
    }
}
