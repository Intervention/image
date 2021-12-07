<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

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
            $frame->getCore()->modulateImage(100 + $this->level, 100, 100);
        }

        return $image;
    }
}
