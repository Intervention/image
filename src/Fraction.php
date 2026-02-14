<?php

declare(strict_types=1);

namespace Intervention\Image;

enum Fraction
{
    case FULL;
    case HALF;
    case THIRD;
    case TWO_THIRDS;
    case QUARTER;
    case TWO_QUARTER;
    case THREE_QUARTER;
    case ONE_AND_A_HALF;
    case DOUBLE;
    case TRIPLE;

    /**
     * Return multiplicator of fraction.
     */
    public function multiplicator(): float
    {
        return match ($this) {
            self::FULL => 1,
            self::HALF => .5,
            self::THIRD => .3333333333,
            self::TWO_THIRDS => .6666666667,
            self::QUARTER => .25,
            self::TWO_QUARTER => .5,
            self::THREE_QUARTER => .75,
            self::ONE_AND_A_HALF => 1.5,
            self::DOUBLE => 2,
            self::TRIPLE => 3,
        };
    }

    /**
     * Calculate fraction of given value.
     */
    public function of(int|float $value): float
    {
        return round($value * $this->multiplicator(), 9);
    }
}
