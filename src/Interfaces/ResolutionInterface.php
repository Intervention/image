<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Length;
use Stringable;

interface ResolutionInterface extends Stringable
{
    /**
     * Factory method to create resolution with given dots per inch.
     */
    public static function dpi(float $x, float $y): self;

    /**
     * Factory method to create resolution with given pixels per inch.
     */
    public static function ppi(float $x, float $y): self;

    /**
     * Convert resolution to units per inch.
     */
    public function perInch(): self;

    /**
     * Convert resolution to units per centimeter.
     */
    public function perCm(): self;

    /**
     * Return resolution of x-axis value.
     */
    public function x(): float;

    /**
     * Return resolution on y-axis value.
     */
    public function y(): float;

    /**
     * Return length unit of resolution.
     */
    public function length(): Length;

    /**
     * Transform object to string.
     */
    public function toString(): string;
}
