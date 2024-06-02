<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\AnimationException;

interface CoreInterface extends CollectionInterface
{
    /**
     * return driver's representation of the image core.
     *
     * @throws AnimationException
     * @return mixed
     */
    public function native(): mixed;

    /**
     * Set driver's representation of the image core.
     *
     * @param mixed $native
     * @return CoreInterface<FrameInterface>
     */
    public function setNative(mixed $native): self;

    /**
     * Count number of frames of animated image core
     *
     * @return int
     */
    public function count(): int;

    /**
     * Return frame of given position in an animated image
     *
     * @param int $position
     * @throws AnimationException
     * @return FrameInterface
     */
    public function frame(int $position): FrameInterface;

    /**
     * Add new frame to core
     *
     * @param FrameInterface $frame
     * @return CoreInterface<FrameInterface>
     */
    public function add(FrameInterface $frame): self;

    /**
     * Return number of repetitions of an animated image
     *
     * @return int
     */
    public function loops(): int;

    /**
     * Set the number of repetitions for an animation. Where a
     * value of 0 means infinite repetition.
     *
     * @param int $loops
     * @return CoreInterface<FrameInterface>
     */
    public function setLoops(int $loops): self;

    /**
     * Get first frame in core
     *
     * @throws AnimationException
     * @return FrameInterface
     */
    public function first(): FrameInterface;

    /**
     * Get last frame in core
     *
     * @throws AnimationException
     * @return FrameInterface
     */
    public function last(): FrameInterface;
}
