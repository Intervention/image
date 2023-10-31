<?php

namespace Intervention\Image\Drivers\Imagick\Traits;

use Imagick;

trait CanReduceColors
{
    /**
     * Returns a Imagick from a given image with reduced colors to a given limit.
     * Reduction is only applied when the given limit is under the given threshold
     *
     * @param Imagick $imagick
     * @param int $limit
     * @param int $threshold
     * @return Imagick
     */
    private function maybeReduceColors(Imagick $imagick, int $limit, int $threshold = 256): Imagick
    {
        if ($limit === 0) {
            return $imagick;
        }

        if ($limit > $threshold) {
            return $imagick;
        }

        $imagick->quantizeImage(
            $limit,
            $imagick->getImageColorspace(),
            0,
            false,
            false
        );

        return $imagick;
    }
}
