<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface FrameInterface
{
    /**
     * Return image data of frame in driver specific format.
     */
    public function native(): mixed;

    /**
     * Set image data of frame in driver specific format.
     */
    public function setNative(mixed $native): self;

    /**
     * Transform frame into an image.
     */
    public function toImage(DriverInterface $driver): ImageInterface;

    /**
     * Get image size of current frame.
     */
    public function size(): SizeInterface;

    /**
     * Return animation delay of current frame in seconds.
     */
    public function delay(): float;

    /**
     * Set animation frame delay in seconds.
     */
    public function setDelay(float $delay): self;

    /**
     * Get disposal method of current frame.
     */
    public function disposalMethod(): int;

    /**
     * Set disposal method of current frame.
     *
     * The disposal method specifies what happens to the current frame when
     * moving to the next. Available method values are:
     *
     * - 0: No disposal, can be used then frames do not have transparency
     * - 1: Leave the frame in place and draw the next on top of it
     * - 2: Clear the frame with the background color before displaying the next frame
     * - 3: Restore the frame to its previous state before the current was drawn
     */
    public function setDisposalMethod(int $method): self;

    /**
     * Set pixel offset of current frame.
     */
    public function setOffset(int $left, int $top): self;

    /**
     * Get left offset in pixels.
     */
    public function offsetLeft(): int;

    /**
     * Set left pixel offset for current frame.
     */
    public function setOffsetLeft(int $offset): self;

    /**
     * Get top pixel offset of current frame.
     */
    public function offsetTop(): int;

    /**
     * Set top pixel offset of current frame.
     */
    public function setOffsetTop(int $offset): self;
}
