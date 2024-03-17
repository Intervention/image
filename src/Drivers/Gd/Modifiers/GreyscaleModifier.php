<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\GreyscaleModifier as GenericGreyscaleModifier;
use Intervention\Image\Traits\IsDriverSpecialized;

class GreyscaleModifier extends GenericGreyscaleModifier implements SpecializedInterface
{
    use IsDriverSpecialized;

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            imagefilter($frame->native(), IMG_FILTER_GRAYSCALE);
        }

        return $image;
    }
}
