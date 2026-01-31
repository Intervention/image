<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Alignment;

interface SizeInterface
{
    /**
     * Get width.
     */
    public function width(): int;

    /**
     * Get height.
     */
    public function height(): int;

    /**
     * Get pivot point.
     */
    public function pivot(): PointInterface;

    /**
     * Set width.
     */
    public function setWidth(int $width): self;

    /**
     * Set height.
     */
    public function setHeight(int $height): self;

    /**
     * Set pivot point.
     */
    public function setPivot(PointInterface $pivot): self;

    /**
     * Calculate aspect ratio of the current size.
     */
    public function aspectRatio(): float;

    /**
     * Determine if current size fits into given size.
     */
    public function fitsInto(self $size): bool;

    /**
     * Determine if size is in landscape format.
     */
    public function isLandscape(): bool;

    /**
     * Determine if size is in portrait format.
     */
    public function isPortrait(): bool;

    /**
     * Move pivot to the given alignment position in the size and adjust the new position by given offset values.
     */
    public function movePivot(string|Alignment $alignment, int $x = 0, int $y = 0): self;

    /**
     * Align pivot relative to given size at given alignment position.
     */
    public function alignPivotTo(self $size, string|Alignment $alignment): self;

    /**
     * Calculate the relative position to another Size based on the pivot point settings of both sizes.
     */
    public function relativePositionTo(self $size): PointInterface;

    /**
     * @see Resizer::resize()
     */
    public function resize(?int $width = null, ?int $height = null): self;

    /**
     * @see Resizer::resizeDown()
     */
    public function resizeDown(?int $width = null, ?int $height = null): self;

    /**
     * @see Resizer::scale()
     */
    public function scale(?int $width = null, ?int $height = null): self;

    /**
     * @see Resizer::scaleDown()
     */
    public function scaleDown(?int $width = null, ?int $height = null): self;

    /**
     * @see Resizer::cover()
     */
    public function cover(int $width, int $height): self;

    /**
     * @see Resizer::contain()
     */
    public function contain(int $width, int $height): self;

    /**
     * @see Resizer::containDown()
     */
    public function containDown(int $width, int $height): self;
}
