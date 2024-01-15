<?php

namespace Intervention\Image\Drivers\Gd;

use GdImage;
use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Interfaces\ModifierInterface;

abstract class SpecializedModifier extends DriverSpecialized implements ModifierInterface
{
    protected function copyResolution(GdImage $source, GdImage $target): void
    {
        $resolution = imageresolution($source);
        if (is_array($resolution) && array_key_exists(0, $resolution) && array_key_exists(1, $resolution)) {
            imageresolution($target, $resolution[0], $resolution[1]);
        }
    }
}
