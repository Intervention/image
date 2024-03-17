<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\BlurModifier as GenericBlurModifier;
use Intervention\Image\Traits\IsDriverSpecialized;

/**
 * @property int $amount
 */
class BlurModifier extends GenericBlurModifier implements SpecializedInterface
{
    use IsDriverSpecialized;

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
