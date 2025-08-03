<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Stringable;

interface ResolutionInterface extends Stringable
{
    /**
     * Return resolution of x-axis
     */
    public function x(): float;

    /**
     * Set resolution on x-axis
     */
    public function setX(float $x): self;

    /**
     * Return resolution on y-axis
     */
    public function y(): float;

    /**
     * Set resolution on y-axis
     */
    public function setY(float $y): self;

    /**
     * Convert the resolution to DPI
     */
    public function perInch(): self;

    /**
     * Convert the resolution to DPCM
     */
    public function perCm(): self;

    /**
     * Return string representation of unit
     */
    public function unit(): string;

    /**
     * Transform object to string
     */
    public function toString(): string;
}
