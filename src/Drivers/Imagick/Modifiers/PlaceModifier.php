<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Interfaces\ImageInterface;

class PlaceModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $watermark = $this->driver()->handleInput($this->element);
        $position = $this->getPosition($image, $watermark);

        foreach ($image as $frame) {
            $frame->native()->compositeImage(
                $watermark->core()->native(),
                Imagick::COMPOSITE_DEFAULT,
                $position->x(),
                $position->y()
            );
        }

        return $image;
    }
}
