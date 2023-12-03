<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

/**
 * @method SizeInterface crop(ImageInterface $image)
 * @property int $offset_x
 * @property int $offset_y
 */
class CropModifier extends DriverSpecializedModifier
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
