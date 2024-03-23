<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\SharpenModifier as GenericSharpenModifier;

class SharpenModifier extends GenericSharpenModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $frame->native()->unsharpMaskImage(1, 1, $this->amount / 6.25, 0);
        }

        return $image;
    }
}
