<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\PlaceModifier as GenericPlaceModifier;

class PlaceModifier extends GenericPlaceModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $watermark = $this->driver()->handleInput($this->element);
        $position = $this->getPosition($image, $watermark);

        // set opacity of watermark
        if ($this->opacity < 100) {
            $watermark->core()->native()->setImageAlphaChannel(Imagick::ALPHACHANNEL_SET);
            $watermark->core()->native()->evaluateImage(
                Imagick::EVALUATE_DIVIDE,
                $this->opacity > 0 ? 100 / $this->opacity : 1000,
                Imagick::CHANNEL_ALPHA,
            );
        }

        foreach ($image as $frame) {
            $frame->native()->compositeImage(
                $watermark->core()->native(),
                Imagick::COMPOSITE_DEFAULT,
                $position->x(),
                $position->y()
            );
        }

        return $image;
    }
}
