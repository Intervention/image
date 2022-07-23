<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use ImagickPixel;
use Intervention\Image\Drivers\Abstract\Modifiers\AbstractDrawModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Geometry\Rectangle;

class DrawRectangleModifier extends AbstractDrawModifier implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        // setup rectangle
        $drawing = new ImagickDraw();
        $drawing->setFillColor($this->getBackgroundColor());
        if ($this->rectangle()->hasBorder()) {
            $drawing->setStrokeColor($this->getBorderColor());
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

    private function rectangle(): Rectangle
    {
        return $this->drawable;
    }

    private function getBackgroundColor(): ImagickPixel
    {
        try {
            $color = $this->handleInput($this->rectangle()->getBackgroundColor());
        } catch (DecoderException $e) {
            $color = null;
        }

        return $this->filterImagickPixel($color);
    }

    private function getBorderColor(): ImagickPixel
    {
        try {
            $color = $this->handleInput($this->rectangle()->getBorderColor());
        } catch (DecoderException $e) {
            $color = null;
        }
        
        return $this->filterImagickPixel($color);
    }
    
    private function filterImagickPixel($color): ImagickPixel
    {
        if (!is_a($color, Color::class)) {
            return new ImagickPixel('transparent');
        }

        return $color->getPixel();
    }
}
