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
            if ($this->ellipse()->hasBorder()) {
                // slightly smaller ellipse to keep 1px bordered edges clean
                if ($this->ellipse()->hasBackgroundColor()) {
                    imagefilledellipse(
                        $frame->getCore(),
                        $this->position->getX(),
                        $this->position->getY(),
                        $this->ellipse()->getWidth() - 1,
                        $this->ellipse()->getHeight() - 1,
                        $this->getBackgroundColor()->toInt()
                    );
                }

                imagesetthickness($frame->getCore(), $this->ellipse()->getBorderSize());

                // gd's imageellipse doesn't respect imagesetthickness so i use
                // imagearc with 359.9 degrees here.
                imagearc(
                    $frame->getCore(),
                    $this->position->getX(),
                    $this->position->getY(),
                    $this->ellipse()->getWidth(),
                    $this->ellipse()->getHeight(),
                    0,
                    359.99,
                    $this->getBorderColor()->toInt()
                );
            } else {
                imagefilledellipse(
                    $frame->getCore(),
                    $this->position->getX(),
                    $this->position->getY(),
                    $this->ellipse()->getWidth(),
                    $this->ellipse()->getHeight(),
                    $this->getBackgroundColor()->toInt()
                );
            }
        });
    }
}
