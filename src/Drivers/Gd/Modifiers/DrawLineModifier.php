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
        return $image->mapFrames(function ($frame) {
            imageline(
                $frame->core(),
                $this->line()->getStart()->x(),
                $this->line()->getStart()->y(),
                $this->line()->getEnd()->x(),
                $this->line()->getEnd()->y(),
                $this->colorToInteger($this->getBackgroundColor())
            );
        });
    }
}
