<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\BackgroundModifier as GenericBackgroundModifier;

class BackgroundModifier extends GenericBackgroundModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $backgroundColor = $this->backgroundColor($this->driver());

        // get imagickpixel from background color
        $pixel = $this->driver()
            ->colorProcessor($image->colorspace())
            ->colorToNative($backgroundColor);

        // merge transparent areas with the background color
        foreach ($image as $frame) {
            $frame->native()->setImageBackgroundColor($pixel);
            $frame->native()->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
            $frame->native()->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
        }

        return $image;
    }
}
