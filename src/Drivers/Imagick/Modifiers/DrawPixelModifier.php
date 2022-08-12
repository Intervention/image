<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Traits\CanHandleInput;

class DrawPixelModifier implements ModifierInterface
{
    use CanHandleInput;

    public function __construct(
        protected Point $position,
        protected $color
    ) {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $color = $this->handleInput($this->color);
        $pixel = new ImagickDraw();
        $pixel->setFillColor($color->getPixel());
        $pixel->point($this->position->getX(), $this->position->getY());

        return $image->eachFrame(function ($frame) use ($pixel) {
            $frame->getCore()->drawImage($pixel);
        });
    }
}
