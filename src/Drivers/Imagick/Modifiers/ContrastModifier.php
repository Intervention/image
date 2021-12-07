<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

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
            $frame->getCore()->sigmoidalContrastImage($this->level > 0, abs($this->level / 4), 0);
        }

        return $image;
    }
}
