<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ResolutionModifier as GenericResolutionModifier;

class ResolutionModifier extends GenericResolutionModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $imagick = $image->core()->native();
        $imagick->setImageResolution($this->x, $this->y);

        return $image;
    }
}
