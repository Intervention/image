<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Interfaces\ImageInterface;

class LimitColorsModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        // no color limit: no reduction
        if ($this->limit === 0) {
            return $image;
        }

        // limit is over threshold: no reduction
        if ($this->limit > $this->threshold) {
            return $image;
        }

        foreach ($image as $frame) {
            $frame->native()->quantizeImage(
                $this->limit,
                $frame->native()->getImageColorspace(),
                0,
                false,
                false
            );
        }

        return $image;
    }
}
