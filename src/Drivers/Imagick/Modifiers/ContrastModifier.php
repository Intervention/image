<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property int $level
 */
class ContrastModifier extends DriverSpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $frame->native()->sigmoidalContrastImage($this->level > 0, abs($this->level / 4), 0);
        }

        return $image;
    }
}
