<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface SizeInterface
{
    /**
     * Get width
     *
     * @return int
     */
    public function width(): int;

    /**
     * Get height
     *
     * @return int
     */
    public function height(): int;

    /**
     * Get pivot point
     *
     * @return PointInterface
     */
    public function pivot(): PointInterface;

    /**
     * Set width
     *
     * @param int $width
     * @return SizeInterface
     */
    public function setWidth(int $width): self;

    /**
     * Set height
     *
     * @param int $height
     * @return SizeInterface
     */
    public function setHeight(int $height): self;

    /**
     * Set pivot point
     *
     * @param PointInterface $pivot
     * @return SizeInterface
     */
    public function setPivot(PointInterface $pivot): self;

    /**
     * Calculate aspect ratio of the current size
     *
     * @return float
     */
    public function aspectRatio(): float;

    /**
     * Determine if current size fits into given size
     *
     * @param SizeInterface $size
     * @return bool
     */
    public function fitsInto(self $size): bool;

    /**
     * Determine if size is in landscape format
     *
     * @return bool
     */
    public function isLandscape(): bool;

    /**
     * Determine if size is in portrait format
     *
     * @return bool
     */
    public function isPortrait(): bool;

    /**
     * Move pivot to given position in size
     *
     * @param string $position
     * @param int $offset_x
     * @param int $offset_y
     * @return SizeInterface
     */
    public function movePivot(string $position, int $offset_x = 0, int $offset_y = 0): self;

    /**
     * Align pivot of current object to given position
     *
     * @param SizeInterface $size
     * @param string $position
     * @return SizeInterface
     */
    public function alignPivotTo(self $size, string $position): self;

    /**
     * Calculate the relative position to another Size
     * based on the pivot point settings of both sizes.
     *
     * @param SizeInterface $size
     * @return PointInterface
     */
    public function relativePositionTo(self $size): PointInterface;

    /**
     * @see ImageInterface::resize()
     */
    public function resize(?int $width = null, ?int $height = null): self;

    /**
     * @see ImageInterface::resizeDown()
     */
    public function resizeDown(?int $width = null, ?int $height = null): self;

    /**
     * @see ImageInterface::scale()
     */
    public function scale(?int $width = null, ?int $height = null): self;

    /**
     * @see ImageInterface::scaleDown()
     */
    public function scaleDown(?int $width = null, ?int $height = null): self;

    /**
     * @see ImageInterface::cover()
     */
    public function cover(int $width, int $height): self;

    /**
     * @see ImageInterface::contain()
     */
    public function contain(int $width, int $height): self;

    public function containMax(int $width, int $height): self;
}
