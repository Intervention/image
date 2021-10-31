<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\Imagick\InputHandler;
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
        $filling = $this->handleInput($this->filling);

        $draw = new ImagickDraw();
        $draw->setFillColor($filling->getPixel());
        $draw->rectangle(0, 0, $image->getWidth(), $image->getHeight());

        foreach ($image as $frame) {
            $frame->getCore()->drawImage($draw);
        }

        return $image;
    }
}
