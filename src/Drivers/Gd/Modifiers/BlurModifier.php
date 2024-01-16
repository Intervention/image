<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

/**
 * @property int $amount
 */
class BlurModifier extends DriverSpecialized implements ModifierInterface
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
