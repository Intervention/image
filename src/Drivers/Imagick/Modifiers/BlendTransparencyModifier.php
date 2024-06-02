<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\BlendTransparencyModifier as GenericBlendTransparencyModifier;

class BlendTransparencyModifier extends GenericBlendTransparencyModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        // decode blending color
        $color = $this->driver()->handleInput(
            $this->color ? $this->color : $this->driver()->config()->blendingColor
        );

        // get imagickpixel from color
        $pixel = $this->driver()
            ->colorProcessor($image->colorspace())
            ->colorToNative($color);

        // merge transparent areas with the background color
        foreach ($image as $frame) {
            $frame->native()->setImageBackgroundColor($pixel);
            $frame->native()->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
            $frame->native()->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
        }

        return $image;
    }
}
