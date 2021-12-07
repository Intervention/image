<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class SharpenModifier implements ModifierInterface
{
    public function __construct(protected int $amount)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $frame->getCore()->unsharpMaskImage(1, 1, $this->amount / 6.25, 0);
        }

        return $image;
    }
}
