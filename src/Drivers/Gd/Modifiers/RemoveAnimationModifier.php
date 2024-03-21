<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\RemoveAnimationModifier as GenericRemoveAnimationModifier;

class RemoveAnimationModifier extends GenericRemoveAnimationModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $image->core()->setNative(
            $this->chosenFrame($image, $this->position)->native()
        );

        return $image;
    }
}
