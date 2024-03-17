<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\RemoveAnimationModifier as GenericRemoveAnimationModifier;
use Intervention\Image\Traits\IsDriverSpecialized;

/**
 * @method mixed chosenFrame(ImageInterface $image, int|string $position)
 * @property int|string $position
 */
class RemoveAnimationModifier extends GenericRemoveAnimationModifier implements SpecializedInterface
{
    use IsDriverSpecialized;

    public function apply(ImageInterface $image): ImageInterface
    {
        $image->core()->setNative(
            $this->chosenFrame($image, $this->position)->native()
        );

        return $image;
    }
}
