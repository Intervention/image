<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface ResolutionInterface
{
    /**
     * Return resolution of x-axis
     *
     * @return float
     */
    public function x(): float;

    /**
     * Set resolution on x-axix
     *
     * @param float $x
     * @return ResolutionInterface
     */
    public function setX(float $x): self;

    /**
     * Return resolution on y-axis
     *
     * @return float
     */
    public function y(): float;

    /**
     * Set resolution on y-axis
     *
     * @param float $y
     * @return ResolutionInterface
     */
    public function setY(float $y): self;

    /**
     * Convert the resolution to DPI
     *
     * @return ResolutionInterface
     */
    public function perInch(): self;

    /**
     * Convert the resolution to DPCM
     *
     * @return ResolutionInterface
     */
    public function perCm(): self;

    /**
     * Return string representation of unit
     *
     * @return string
     */
    public function unit(): string;

    /**
     * Transform object to string
     *
     * @return string
     */
    public function toString(): string;

    /**
     * Cast object to string
     *
     * @return string
     */
    public function __toString(): string;
}
