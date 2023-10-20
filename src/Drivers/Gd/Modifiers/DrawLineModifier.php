<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractDrawModifier;
use Intervention\Image\Drivers\Gd\Traits\CanHandleColors;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class DrawLineModifier extends AbstractDrawModifier implements ModifierInterface
{
    use CanHandleColors;

    public function apply(ImageInterface $image): ImageInterface
    {
        return $image->eachFrame(function ($frame) {
            imageline(
                $frame->getCore(),
                $this->line()->getStart()->getX(),
                $this->line()->getStart()->getY(),
                $this->line()->getEnd()->getX(),
                $this->line()->getEnd()->getY(),
                $this->colorToInteger($this->getBackgroundColor())
            );
        });
    }
}
