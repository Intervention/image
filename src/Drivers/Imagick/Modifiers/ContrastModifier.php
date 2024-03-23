<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ContrastModifier as GenericContrastModifier;

class ContrastModifier extends GenericContrastModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $frame->native()->sigmoidalContrastImage($this->level > 0, abs($this->level / 4), 0);
        }

        return $image;
    }
}
