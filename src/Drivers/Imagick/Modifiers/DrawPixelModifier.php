<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\Imagick\Traits\CanHandleColors;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Traits\CanCheckType;
use Intervention\Image\Traits\CanHandleInput;

class DrawPixelModifier implements ModifierInterface
{
    use CanHandleInput;
    use CanHandleColors;
    use CanCheckType;

    public function __construct(protected Point $position, protected mixed $color)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $color = $this->failIfNotInstance(
            $this->handleInput($this->color),
            ColorInterface::class
        );

        $pixel = new ImagickDraw();
        $pixel->setFillColor($this->colorToPixel($color, $image->colorspace()));
        $pixel->point($this->position->x(), $this->position->y());

        return $image->mapFrames(function ($frame) use ($pixel) {
            $frame->core()->drawImage($pixel);
        });
    }
}
