<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class ContrastModifier implements ModifierInterface
{
    public function __construct(protected int $level)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            imagefilter($frame->getCore(), IMG_FILTER_CONTRAST, ($this->level * -1));
        }

        return $image;
    }
}
