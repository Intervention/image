<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\BlendTransparencyModifier as GenericBlendTransparencyModifier;

class BlendTransparencyModifier extends GenericBlendTransparencyModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $blendingColor = $this->blendingColor($this->driver());

        foreach ($image as $frame) {
            // create new canvas with blending color as background
            $modified = Cloner::cloneBlended(
                $frame->native(),
                background: $blendingColor
            );

            // set new gd image
            $frame->setNative($modified);
        }

        return $image;
    }
}
