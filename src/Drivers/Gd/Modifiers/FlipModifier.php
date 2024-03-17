<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\FlipModifier as GenericFlipModifier;
use Intervention\Image\Traits\IsDriverSpecialized;

class FlipModifier extends GenericFlipModifier implements SpecializedInterface
{
    use IsDriverSpecialized;

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            imageflip($frame->native(), IMG_FLIP_VERTICAL);
        }

        return $image;
    }
}
