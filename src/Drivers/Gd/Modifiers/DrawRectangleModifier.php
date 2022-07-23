<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractDrawModifier;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Geometry\Rectangle;
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
                    $this->position->getX() + $this->rectangle()->bottomRightPoint()->getY(),
                    $this->position->getY() + $this->rectangle()->bottomRightPoint()->getY(),
                    $this->getBackgroundColor()
                );
            }

            if ($this->rectangle()->hasBorder()) {
                // draw border
                imagesetthickness($frame->getCore(), $this->rectangle()->getBorderSize());
                imagerectangle(
                    $frame->getCore(),
                    $this->position->getX(),
                    $this->position->getY(),
                    $this->position->getX() + $this->rectangle()->bottomRightPoint()->getY(),
                    $this->position->getY() + $this->rectangle()->bottomRightPoint()->getY(),
                    $this->getBorderColor()
                );
            }
        });

        return $image;
    }

    private function rectangle(): Rectangle
    {
        return $this->drawable;
    }

    private function getBackgroundColor(): int
    {
        try {
            $color = $this->handleInput($this->rectangle()->getBackgroundColor());
        } catch (DecoderException $e) {
            return 2130706432; // transparent
        }

        return $color->toInt();
    }

    private function getBorderColor(): int
    {
        try {
            $color = $this->handleInput($this->rectangle()->getBorderColor());
        } catch (DecoderException $e) {
            return 2130706432; // transparent
        }

        return $color->toInt();
    }
}
