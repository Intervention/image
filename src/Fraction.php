<?php

declare(strict_types=1);

namespace Intervention\Image;

enum Fraction
{
    case HALF;
    case THIRD;
    case TWO_THIRDS;
    case QUARTER;
    case TWO_QUARTER;
    case THREE_QUARTER;

    public function multiplicator(): float
    {
        return match ($this) {
            self::HALF => .5,
            self::THIRD => .3333333333,
            self::TWO_THIRDS => .6666666667,
            self::QUARTER => .25,
            self::TWO_QUARTER => .5,
            self::THREE_QUARTER => .75,
        };
    }

    /**
     * Calculate fraction of given value
     */
    public function of(int|float $value): float
    {
        return $value * $this->multiplicator();
    }
}
