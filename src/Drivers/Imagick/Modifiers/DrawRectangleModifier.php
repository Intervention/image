<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\Abstract\Modifiers\AbstractDrawModifier;
use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class DrawRectangleModifier extends AbstractDrawModifier implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        // setup
        $drawing = new ImagickDraw();
        $background_color = $this->failIfNotClass($this->getBackgroundColor(), Color::class);
        $border_color = $this->failIfNotClass($this->getBorderColor(), Color::class);

        $drawing->setFillColor($background_color->getPixel());
        if ($this->rectangle()->hasBorder()) {
            $drawing->setStrokeColor($border_color->getPixel());
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
