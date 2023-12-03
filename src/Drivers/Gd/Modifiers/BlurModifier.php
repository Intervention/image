<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property int $amount
 */
class BlurModifier extends DriverSpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            for ($i = 0; $i < $this->amount; $i++) {
                imagefilter($frame->native(), IMG_FILTER_GAUSSIAN_BLUR);
            }
        }

        return $image;
    }
}
