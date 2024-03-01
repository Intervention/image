<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\ColorException;

interface ColorChannelInterface
{
    /**
     * Create new instance by either value or normalized value
     *
     * @param int|null $value
     * @param float|null $normalized
     * @throws ColorException
     */
    public function __construct(?int $value = null, ?float $normalized = null);

    /**
     * Return color channels integer value
     *
     * @return int
     */
    public function value(): int;

    /**
     * Return the channels value normalized to a float value form 0 to 1 by its range
     *
     * @return float
     */
    public function normalize(int $precision = 32): float;

    /**
     * Throw exception if the given value is not applicable for channel
     * otherwise the value is returned unchanged.
     *
     * @throws ColorException
     * @return mixed
     */
    public function validate(mixed $value): mixed;

    /**
     * Return the the minimal possible value of the color channel
     *
     * @return int
     */
    public function min(): int;

    /*
     * Return the the maximal possible value of the color channel
     *
     * @return int
     */
    public function max(): int;

    /**
     * Cast color channel's value to string
     *
     * @return string
     */
    public function toString(): string;

    /**
     * Cast color channel's value to string
     *
     * @return string
     */
    public function __toString(): string;
}
