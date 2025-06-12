<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\RuntimeException;

interface FrameInterface
{
    /**
     * Return image data of frame in driver specific format
     */
    public function native(): mixed;

    /**
     * Set image data of drame in driver specific format
     */
    public function setNative(mixed $native): self;

    /**
     * Transform frame into an image
     *
     * @throws RuntimeException
     */
    public function toImage(DriverInterface $driver): ImageInterface;

    /**
     * Get image size of current frame
     */
    public function size(): SizeInterface;

    /**
     * Return animation delay of current frame in seconds
     */
    public function delay(): float;

    /**
     * Set animation frame delay in seoncds
     */
    public function setDelay(float $delay): self;

    /**
     * Get disposal method of current frame
     */
    public function dispose(): int;

    /**
     * Set disposal method of current frame
     */
    public function setDispose(int $dispose): self;

    /**
     * Set pixel offset of current frame
     */
    public function setOffset(int $left, int $top): self;

    /**
     * Get left offset in pixels
     */
    public function offsetLeft(): int;

    /**
     * Set left pixel offset for current frame
     */
    public function setOffsetLeft(int $offset): self;

    /**
     * Get top pixel offset of current frame
     */
    public function offsetTop(): int;

    /**
     * Set top pixel offset of current frame
     */
    public function setOffsetTop(int $offset): self;
}
