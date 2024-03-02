<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\FontInterface;

abstract class AbstractTextModifier extends DriverSpecialized
{
    /**
     * Return array of offset points to draw text stroke effect below the actual text
     *
     * @param FontInterface $font
     * @return array
     */
    protected function strokeOffsets(FontInterface $font): array
    {
        $offsets = [];

        if ($font->strokeWidth() <= 0) {
            return $offsets;
        }

        for ($x = $font->strokeWidth() * -1; $x <= $font->strokeWidth(); $x++) {
            for ($y = $font->strokeWidth() * -1; $y <= $font->strokeWidth(); $y++) {
                $offsets[] = new Point($x, $y);
            }
        }

        return $offsets;
    }
}
