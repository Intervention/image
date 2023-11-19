<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ImageInterface;

class RemoveAnimationModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        // create new imagick with just one image
        $imagick = new Imagick();
        $frame = $this->chosenFrame($image, $this->position);
        $imagick->addImage($frame->data()->getImage());

        return new Image(
            $image->driver(),
            new Core($imagick),
            $image->exif()
        );
    }
}
