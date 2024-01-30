<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

/**
 * @property mixed $color
 */
class BlendTransparencyModifier extends DriverSpecialized implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        // decode blending color
        $color = $this->driver()->handleInput(
            $this->color ? $this->color : $image->blendingColor()
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
