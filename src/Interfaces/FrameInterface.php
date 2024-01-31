<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface FrameInterface
{
    /**
     * Return image data of frame in driver specific format
     *
     * @return mixed
     */
    public function native(): mixed;

    /**
     * Set image data of drame in driver specific format
     *
     * @param mixed $native
     * @return FrameInterface
     */
    public function setNative($native): self;

    /**
     * Transform frame into an image
     *
     * @param DriverInterface $driver
     * @return ImageInterface
     */
    public function toImage(DriverInterface $driver): ImageInterface;

    /**
     * Get image size of current frame
     *
     * @return SizeInterface
     */
    public function size(): SizeInterface;

    /**
     * Return animation delay of current frame in seconds
     *
     * @return float
     */
    public function delay(): float;

    /**
     * Set animation frame delay in seoncds
     *
     * @param float $delay
     * @return FrameInterface
     */
    public function setDelay(float $delay): self;

    /**
     * Get disposal method of current frame
     *
     * @return int
     */
    public function dispose(): int;

    /**
     * Set disposal method of current frame
     *
     * @return FrameInterface
     */
    public function setDispose(int $dispose): self;

    /**
     * Set pixel offset of current frame
     *
     * @param int $left
     * @param int $top
     * @return FrameInterface
     */
    public function setOffset(int $left, int $top): self;

    /**
     * Get left offset in pixels
     *
     * @return int
     */
    public function offsetLeft(): int;

    /**
     * Set left pixel offset for current frame
     *
     * @param int $offset
     * @return FrameInterface
     */
    public function setOffsetLeft(int $offset): self;

    /**
     * Get top pixel offset of current frame
     *
     * @return int
     */
    public function offsetTop(): int;

    /**
     * Set top pixel offset of current frame
     *
     * @param int $offset
     * @return FrameInterface
     */
    public function setOffsetTop(int $offset): self;
}
