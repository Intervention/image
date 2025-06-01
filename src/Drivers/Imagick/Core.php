<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickException;
use Iterator;
use Intervention\Image\Interfaces\CoreInterface;
use Intervention\Image\Exceptions\AnimationException;
use Intervention\Image\Interfaces\CollectionInterface;
use Intervention\Image\Interfaces\FrameInterface;

/**
 * @implements Iterator<FrameInterface>
 */
class Core implements CoreInterface, Iterator
{
    protected int $iteratorIndex = 0;

    /**
     * Create new core instance
     *
     * @return void
     */
    public function __construct(protected Imagick $imagick)
    {
        //
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::has()
     */
    public function has(int|string $key): bool
    {
        try {
            $result = $this->imagick->setIteratorIndex($key);
        } catch (ImagickException) {
            return false;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::push()
     */
    public function push(mixed $item): CollectionInterface
    {
        return $this->add($item);
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::get()
     */
    public function get(int|string $key, mixed $default = null): mixed
    {
        try {
            $this->imagick->setIteratorIndex($key);
        } catch (ImagickException) {
            return $default;
        }

        return new Frame($this->imagick->current());
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::getAtPosition()
     */
    public function getAtPosition(int $key = 0, mixed $default = null): mixed
    {
        return $this->get($key, $default);
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::empty()
     */
    public function empty(): CollectionInterface
    {
        $this->imagick->clear();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::slice()
     */
    public function slice(int $offset, ?int $length = null): CollectionInterface
    {
        $allowed_indexes = [];
        $length = is_null($length) ? $this->count() : $length;
        for ($i = $offset; $i < $offset + $length; $i++) {
            $allowed_indexes[] = $i;
        }

        $sliced = new Imagick();
        foreach ($this->imagick as $key => $native) {
            if (in_array($key, $allowed_indexes)) {
                $sliced->addImage($native->getImage());
            }
        }

        $sliced = $sliced->coalesceImages();
        $sliced->setImageIterations($this->imagick->getImageIterations());

        $this->imagick = $sliced;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::add()
     */
    public function add(FrameInterface $frame): CoreInterface
    {
        $imagick = $frame->native();

        $imagick->setImageDelay(
            (int) round($frame->delay() * 100)
        );

        $imagick->setImageDispose($frame->dispose());

        $size = $frame->size();
        $imagick->setImagePage(
            $size->width(),
            $size->height(),
            $frame->offsetLeft(),
            $frame->offsetTop()
        );

        $this->imagick->addImage($imagick);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::count()
     */
    public function count(): int
    {
        return $this->imagick->getNumberImages();
    }

    /**
     * {@inheritdoc}
     *
     * @see Iterator::rewind()
     */
    public function current(): mixed
    {
        $this->imagick->setIteratorIndex($this->iteratorIndex);

        return new Frame($this->imagick->current());
    }

    /**
     * {@inheritdoc}
     *
     * @see Iterator::rewind()
     */
    public function next(): void
    {
        $this->iteratorIndex += 1;
    }

    /**
     * {@inheritdoc}
     *
     * @see Iterator::rewind()
     */
    public function key(): mixed
    {
        return $this->iteratorIndex;
    }

    /**
     * {@inheritdoc}
     *
     * @see Iterator::rewind()
     */
    public function valid(): bool
    {
        try {
            $result = $this->imagick->setIteratorIndex($this->iteratorIndex);
        } catch (ImagickException) {
            return false;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see Iterator::rewind()
     */
    public function rewind(): void
    {
        $this->iteratorIndex = 0;
    }

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::native()
     */
    public function native(): mixed
    {
        return $this->imagick;
    }

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::setNative()
     */
    public function setNative(mixed $native): CoreInterface
    {
        $this->imagick = $native;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::frame()
     */
    public function frame(int $position): FrameInterface
    {
        foreach ($this->imagick as $core) {
            if ($core->getIteratorIndex() === $position) {
                return new Frame($core);
            }
        }

        throw new AnimationException('Frame #' . $position . ' could not be found in the image.');
    }

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::loops()
     */
    public function loops(): int
    {
        return $this->imagick->getImageIterations();
    }

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::setLoops()
     */
    public function setLoops(int $loops): CoreInterface
    {
        $this->imagick = $this->imagick->coalesceImages();
        $this->imagick->setImageIterations($loops);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::first()
     */
    public function first(): FrameInterface
    {
        return $this->frame(0);
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectableInterface::last()
     */
    public function last(): FrameInterface
    {
        return $this->frame($this->count() - 1);
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::toArray()
     */
    public function toArray(): array
    {
        $frames = [];

        foreach ($this as $frame) {
            $frames[] = $frame;
        }

        return $frames;
    }

    /**
     * Clone instance
     */
    public function __clone(): void
    {
        $this->imagick = clone $this->imagick;
    }
}
