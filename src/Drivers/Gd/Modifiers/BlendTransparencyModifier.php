<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\BlendTransparencyModifier as GenericBlendTransparencyModifier;
use Intervention\Image\Traits\IsDriverSpecialized;

/**
 * @property mixed $color
 */
class BlendTransparencyModifier extends GenericBlendTransparencyModifier implements SpecializedInterface
{
    use IsDriverSpecialized;

    public function apply(ImageInterface $image): ImageInterface
    {
        // decode blending color
        $color = $this->driver()->handleInput(
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
