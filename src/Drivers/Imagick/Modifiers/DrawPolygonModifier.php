<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\Abstract\Modifiers\AbstractDrawModifier;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class DrawPolygonModifier extends AbstractDrawModifier implements ModifierInterface
{
    public function __construct(
        protected DrawableInterface $drawable
    ) {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $drawing = new ImagickDraw();
        if ($this->polygon()->hasBackgroundColor()) {
            $drawing->setFillColor($this->getBackgroundColor()->getPixel());
        }

        if ($this->polygon()->hasBorder()) {
            $drawing->setStrokeColor($this->getBorderColor()->getPixel());
            $drawing->setStrokeWidth($this->polygon()->getBorderSize());
        }

        $drawing->polygon($this->points());

        return $image->eachFrame(function ($frame) use ($drawing) {
            $frame->getCore()->drawImage($drawing);
        });
    }

    private function points(): array
    {
        $points = [];
        foreach ($this->polygon() as $point) {
            $points[] = ['x' => $point->getX(), 'y' => $point->getY()];
        }

        return $points;
    }
}
