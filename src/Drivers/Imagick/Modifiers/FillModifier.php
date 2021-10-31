<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\Imagick\InputHandler;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Traits\CanResolveDriverClass;

class FillModifier implements ModifierInterface
{
    use CanResolveDriverClass;

    public function __construct($filling, ?int $x = null, ?int $y = null)
    {
        $this->filling = $filling;
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $filling = $this->getApplicableFilling();

        $draw = new ImagickDraw();
        $draw->setFillColor($filling->getPixel());
        $draw->rectangle(0, 0, $image->width(), $image->height());

        foreach ($image as $frame) {
            $frame->getCore()->drawImage($draw);
        }

        return $image;
    }

    protected function getApplicableFilling(): ColorInterface
    {
        return $this->resolveDriverClass('InputHandler')->handle($this->filling);
    }
}
