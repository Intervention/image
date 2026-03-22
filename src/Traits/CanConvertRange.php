<?php

declare(strict_types=1);

namespace Intervention\Image\Traits;

trait CanConvertRange
{
    /**
     * Convert input in range (min) to (max) to the corresponding value
     * in target range (targetMin) to (targetMax).
     */
    public static function convertRange(
        float|int $input,
        float|int $min,
        float|int $max,
        float|int $targetMin,
        float|int $targetMax
    ): float {
        return ((($input - $min) * ($targetMax - $targetMin)) / ($max - $min)) + $targetMin;
    }
}
