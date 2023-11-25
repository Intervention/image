<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property int $x
 * @property int $y
 */
class ResolutionModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $imagick = $image->core()->native();
        $imagick->setImageResolution($this->x, $this->y);

        return $image;
    }
}
