<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Traits\CanCheckType;
use Intervention\Image\Traits\CanHandleInput;

class DrawPixelModifier implements ModifierInterface
{
    use CanHandleInput;
    use CanCheckType;

    public function __construct(protected Point $position, protected mixed $color)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $color = $this->failIfNotClass(
            $this->handleInput($this->color),
            Color::class,
        );

        $pixel = new ImagickDraw();
        $pixel->setFillColor($color->getPixel());
        $pixel->point($this->position->getX(), $this->position->getY());

        return $image->eachFrame(function ($frame) use ($pixel) {
            $frame->getCore()->drawImage($pixel);
        });
    }
}
