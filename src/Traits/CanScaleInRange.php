<?php

declare(strict_types=1);

namespace Intervention\Image\Traits;

use Intervention\Image\Exceptions\InvalidArgumentException;

trait CanScaleInRange
{
    /**
     * Scale the specified value by the given percentage of -100 to 100 within the given range.
     */
    private function scaleInRange(int|float $value, int $percent, int|float $min, int|float $max): float
    {
        if ($percent === 0) {
            return $value;
        }

        if ($percent < -100 || $percent > 100) {
            throw new InvalidArgumentException('Percentage value must be between -100 and 100');
        }

        if ($value < $min || $value > $max) {
            throw new InvalidArgumentException('Value must be between ' . $min . ' and ' . $max);
        }

        return $value + (($percent < 0 ? $min - $value : $max - $value) / 100 * abs($percent));
    }
}
