<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractDrawModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class DrawEllipseModifier extends AbstractDrawModifier implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        return $image->eachFrame(function ($frame) {
            if ($this->drawable()->hasBorder()) {
                // slightly smaller ellipse to keep 1px bordered edges clean
                if ($this->drawable()->hasBackgroundColor()) {
                    imagefilledellipse(
                        $frame->getCore(),
                        $this->position->getX(),
                        $this->position->getY(),
                        $this->drawable()->getWidth() - 1,
                        $this->drawable()->getHeight() - 1,
                        $this->getBackgroundColor()->toInt()
                    );
                }

                imagesetthickness($frame->getCore(), $this->drawable()->getBorderSize());

                // gd's imageellipse doesn't respect imagesetthickness so i use
                // imagearc with 359.9 degrees here.
                imagearc(
                    $frame->getCore(),
                    $this->position->getX(),
                    $this->position->getY(),
                    $this->drawable()->getWidth(),
                    $this->drawable()->getHeight(),
                    0,
                    359.99,
                    $this->getBorderColor()->toInt()
                );
            } else {
                imagefilledellipse(
                    $frame->getCore(),
                    $this->position->getX(),
                    $this->position->getY(),
                    $this->drawable()->getWidth(),
                    $this->drawable()->getHeight(),
                    $this->getBackgroundColor()->toInt()
                );
            }
        });
    }
}
