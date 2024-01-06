<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property mixed $color
 */
class BlendTransparencyModifier extends DriverSpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        // decode blending color
        $color =  $this->driver()->handleInput(
            $this->color ? $this->color : $image->blendingColor()
        );

        foreach ($image as $frame) {
            // create new canvas with blending color as background
            $modified = Cloner::cloneBlended(
                $frame->native(),
                background: $color
            );

            // set new gd image
            $frame->setNative($modified);
        }

        return $image;
    }
}
