<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Interfaces\ImageInterface;

class CropModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->crop($image);

        foreach ($image as $frame) {
            $frame->native()->extentImage(
                $crop->width(),
                $crop->height(),
                $crop->pivot()->x() + $this->offset_x,
                $crop->pivot()->y() + $this->offset_y
            );
        }

        return $image;
    }
}
