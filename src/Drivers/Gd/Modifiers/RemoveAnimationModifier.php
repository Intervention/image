<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Interfaces\ImageInterface;

class RemoveAnimationModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $image->core()->setNative(
            $this->chosenFrame($image, $this->position)->native()
        );

        return $image;
    }
}
