<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractRotateModifier;
use Intervention\Image\Drivers\Gd\Traits\CanHandleColors;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class RotateModifier extends AbstractRotateModifier implements ModifierInterface
{
    use CanHandleColors;

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            // retain resolution because imagerotate() seems to reset density to 96dpi
            $resolution = imageresolution($frame->core());

            // rotate image
            $frame->setCore(
                imagerotate(
                    $frame->core(),
                    $this->rotationAngle(),
                    $this->colorToInteger($this->backgroundColor())
                )
            );

            // restore original image resolution
            imageresolution($frame->core(), $resolution[0], $resolution[1]);
        }

        return $image;
    }
}
