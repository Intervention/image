<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\InputHandler;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Traits\CanHandleInput;

class FillModifier implements ModifierInterface
{
    use CanHandleInput;

    public function __construct($filling, ?int $x = null, ?int $y = null)
    {
        $this->filling = $filling;
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $width = $image->getWidth();
        $height = $image->getHeight();
        $filling = $this->handleInput($this->filling);

        foreach ($image as $frame) {
            imagefilledrectangle($frame->getCore(), 0, 0, $width - 1, $height - 1, $filling);
        }

        return $image;
    }
}
