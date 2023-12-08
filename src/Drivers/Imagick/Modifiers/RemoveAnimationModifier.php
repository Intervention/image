<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @method mixed chosenFrame(ImageInterface $image, int|string $position)
 * @property int|string $position
 */
class RemoveAnimationModifier extends DriverSpecializedModifier
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
