<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Collection;
use Intervention\Image\Exceptions\AnimationException;
use Intervention\Image\Interfaces\CoreInterface;
use Intervention\Image\Interfaces\FrameInterface;

class Core extends Collection implements CoreInterface
{
    protected int $loops = 0;

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::add()
     */
    public function add(FrameInterface $frame): CoreInterface
    {
        $this->push($frame);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::native()
     */
    public function native(): mixed
    {
        return $this->first()->native();
    }

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::setNative()
     */
    public function setNative(mixed $native): self
    {
        $this->empty()->push(new Frame($native));

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::frame()
     */
    public function frame(int $position): FrameInterface
    {
        $frame = $this->getAtPosition($position);

        if (!($frame instanceof FrameInterface)) {
            throw new AnimationException('Frame #' . $position . ' could not be found in the image.');
        }

        return $frame;
    }

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::loops()
     */
    public function loops(): int
    {
        return $this->loops;
    }

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::setLoops()
     */
    public function setLoops(int $loops): self
    {
        $this->loops = $loops;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::first()
     */
    public function first(): FrameInterface
    {
        return parent::first();
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::last()
     */
    public function last(): FrameInterface
    {
        return parent::last();
    }

    /**
     * Clone instance
     *
     * @return void
     */
    public function __clone(): void
    {
        foreach ($this->items as $key => $frame) {
            $this->items[$key] = clone $frame;
        }
    }
}
