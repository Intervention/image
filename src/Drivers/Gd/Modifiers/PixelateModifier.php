<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class PixelateModifier implements ModifierInterface
{
    public function __construct(protected int $size)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            imagefilter($frame->getCore(), IMG_FILTER_PIXELATE, $this->size, true);
        }

        return $image;
    }
}
