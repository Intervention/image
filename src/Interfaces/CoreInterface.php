<?php

namespace Intervention\Image\Interfaces;

use Traversable;

interface CoreInterface extends Traversable
{
    /**
     * return driver's representation of the image core.
     *
     * @return mixed
     */
    public function native(): mixed;

    /**
     * Set driver's representation of the image core.
     *
     * @param mixed $native
     * @return CoreInterface
     */
    public function setNative(mixed $native): CoreInterface;

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
     * @return FrameInterface
     */
    public function frame(int $position): FrameInterface;

    /**
     * Add new frame to core
     *
     * @param FrameInterface $frame
     * @return CoreInterface
     */
    public function add(FrameInterface $frame): CoreInterface;

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
     * @return CoreInterface
     */
    public function setLoops(int $loops): CoreInterface;

    public function first(): FrameInterface;
}
