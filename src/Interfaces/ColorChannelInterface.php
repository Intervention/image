<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Stringable;

interface ColorChannelInterface extends Stringable
{
    /**
     * Create color channel from normalized (0.0 - 1.0) value.
     */
    public static function fromNormalized(float $normalized): self;

    /**
     * Return the the minimal possible value of the color channel.
     */
    public static function min(): float;

    /*
     * Return the the maximal possible value of the color channel.
     *
     * @return int
     */
    public static function max(): float;

    /**
     * Return color channels value.
     */
    public function value(): int|float;

    /**
     * Return the channels value normalized to a float value from 0.0 to 1.0 by its range.
     */
    public function normalizedValue(int $precision = 32): float;

    /**
     * Transform color channel's value to string.
     */
    public function toString(): string;
}
