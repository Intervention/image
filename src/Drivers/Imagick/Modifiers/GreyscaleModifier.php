<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class GreyscaleModifier extends DriverSpecialized implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $frame->native()->modulateImage(100, 0, 100);
        }

        return $image;
    }
}
