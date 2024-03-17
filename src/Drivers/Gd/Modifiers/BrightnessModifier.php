<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\BrightnessModifier as GenericBrightnessModifier;
use Intervention\Image\Traits\IsDriverSpecialized;

/**
 * @property int $level
 */
class BrightnessModifier extends GenericBrightnessModifier implements SpecializedInterface
{
    use IsDriverSpecialized;

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            imagefilter($frame->native(), IMG_FILTER_BRIGHTNESS, intval($this->level * 2.55));
        }

        return $image;
    }
}
