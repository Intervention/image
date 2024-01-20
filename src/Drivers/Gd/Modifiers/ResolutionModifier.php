<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

/**
 * @property int $x
 * @property int $y
 */
class ResolutionModifier extends DriverSpecialized implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $x = intval(round($this->x));
        $y = intval(round($this->y));

        foreach ($image as $frame) {
            imageresolution($frame->native(), $x, $y);
        }

        return $image;
    }
}
