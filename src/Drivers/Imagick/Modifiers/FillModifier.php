<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\Imagick\InputHandler;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class FillModifier implements ModifierInterface
{
    public function __construct($filling, ?int $x = null, ?int $y = null)
    {
        $this->filling = $filling;
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $draw = new ImagickDraw();
        $draw->setFillColor($this->getApplicableFilling()->getPixel());
        $draw->rectangle(0, 0, $image->getWidth(), $image->getHeight());

        foreach ($image as $frame) {
            $frame->getCore()->drawImage($draw);
        }

        return $image;
    }

    protected function getApplicableFilling()
    {
        return (new InputHandler())->handle($this->filling);
    }
}
