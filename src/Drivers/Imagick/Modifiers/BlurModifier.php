<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class BlurModifier implements ModifierInterface
{
    public function __construct(protected int $amount)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $frame->getCore()->blurImage(1 * $this->amount, 0.5 * $this->amount);
        }

        return $image;
    }
}
