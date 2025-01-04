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
        $blendingColor = $this->blendingColor($this->driver());

        // get imagickpixel from blending color
        $pixel = $this->driver()
            ->colorProcessor($image->colorspace())
            ->colorToNative($blendingColor);

        // merge transparent areas with the background color
        foreach ($image as $frame) {
            $frame->native()->setImageBackgroundColor($pixel);
            $frame->native()->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
            $frame->native()->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
        }

        return $image;
    }
}
