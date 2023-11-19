<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Drivers\Gd\Core;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ImageInterface;

class RemoveAnimationModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        return new Image(
            $image->driver(),
            new Core([
                $this->chosenFrame($image, $this->position)
            ]),
            $image->exif()
        );
    }
}
