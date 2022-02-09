<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class BrightnessModifier implements ModifierInterface
{
    public function __construct(protected int $level)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            imagefilter($frame->getCore(), IMG_FILTER_BRIGHTNESS, intval($this->level * 2.55));
        }

        return $image;
    }
}
