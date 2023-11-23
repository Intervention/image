<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Interfaces\ImageInterface;

class PlaceModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $watermark = $this->driver()->handleInput($this->element);
        $position = $this->getPosition($image, $watermark);

        foreach ($image as $frame) {
            imagealphablending($frame->native(), true);
            imagecopy(
                $frame->native(),
                $watermark->core()->native(),
                $position->x(),
                $position->y(),
                0,
                0,
                $watermark->width(),
                $watermark->height()
            );
        }

        return $image;
    }
}
