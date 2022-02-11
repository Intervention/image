<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class GammaModifier implements ModifierInterface
{
    public function __construct(protected float $gamma)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            imagegammacorrect($frame->getCore(), 1, $this->gamma);
        }

        return $image;
    }
}
