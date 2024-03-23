<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\RemoveAnimationModifier as GenericRemoveAnimationModifier;

class RemoveAnimationModifier extends GenericRemoveAnimationModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        // create new imagick with just one image
        $imagick = new Imagick();
        $frame = $this->chosenFrame($image, $this->position);
        $imagick->addImage($frame->native()->getImage());

        // set new imagick to image
        $image->core()->setNative($imagick);

        return $image;
    }
}
