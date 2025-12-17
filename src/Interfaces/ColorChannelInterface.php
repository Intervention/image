<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Stringable;

interface ColorChannelInterface extends Stringable
{
    /**
     * Create new instance by either value or normalized value
     */
    public function __construct(?int $value = null, ?float $normalized = null);

    /**
     * Return color channels integer value
     */
    public function value(): int;

    /**
     * Return the channels value normalized to a float value form 0 to 1 by its range
     */
    public function normalize(int $precision = 32): float;

    /**
     * Return the the minimal possible value of the color channel
     */
    public function min(): int;

    /*
     * Return the the maximal possible value of the color channel
     *
     * @return int
     */
    public function max(): int;

    /**
     * Transform color channel's value to string
     */
    public function toString(): string;
}
