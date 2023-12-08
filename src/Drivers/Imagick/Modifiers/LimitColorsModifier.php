<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property int $limit
 * @property int $threshold
 */
class LimitColorsModifier extends DriverSpecializedModifier
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
