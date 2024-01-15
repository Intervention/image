<?php

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickException;
use Iterator;
use Intervention\Image\Interfaces\CoreInterface;
use Intervention\Image\Exceptions\AnimationException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\CollectionInterface;
use Intervention\Image\Interfaces\FrameInterface;

class Core implements CoreInterface, Iterator
{
    protected int $iteratorIndex = 0;

    public function __construct(protected Imagick $imagick)
    {
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
    public function push($item): CollectionInterface
    {
        return $this->add($item);
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::get()
     */
    public function get(int|string $key, $default = null): mixed
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
    public function getAtPosition(int $key = 0, $default = null): mixed
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
        if ($offset >= $this->count()) {
            throw new RuntimeException('Offset exceeds the maximum value.');
        }

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

    public function add(FrameInterface $frame): CoreInterface
    {
        $imagick = $frame->native();

        $imagick->setImageDelay($frame->delay());
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

    public function count(): int
    {
        return $this->imagick->getNumberImages();
    }

    public function current(): mixed
    {
        $this->imagick->setIteratorIndex($this->iteratorIndex);

        return new Frame($this->imagick->current());
    }

    public function next(): void
    {
        $this->iteratorIndex = $this->iteratorIndex + 1;
    }

    public function key(): mixed
    {
        return $this->iteratorIndex;
    }

    public function valid(): bool
    {
        try {
            $result = $this->imagick->setIteratorIndex($this->iteratorIndex);
        } catch (ImagickException) {
            return false;
        }

        return $result;
    }

    public function rewind(): void
    {
        $this->iteratorIndex = 0;
    }

    public function native(): mixed
    {
        return $this->imagick;
    }

    public function setNative(mixed $native): CoreInterface
    {
        $this->imagick = $native;

        return $this;
    }

    public function frame(int $position): FrameInterface
    {
        foreach ($this->imagick as $core) {
            if ($core->getIteratorIndex() == $position) {
                return new Frame($core);
            }
        }

        throw new AnimationException('Frame #' . $position . ' could not be found in the image.');
    }

    public function loops(): int
    {
        return $this->imagick->getImageIterations();
    }

    public function setLoops(int $loops): CoreInterface
    {
        $this->imagick = $this->imagick->coalesceImages();
        $this->imagick->setImageIterations($loops);

        return $this;
    }

    public function first(): FrameInterface
    {
        return $this->frame(0);
    }

    public function last(): FrameInterface
    {
        return $this->frame($this->count() - 1);
    }

    public function __clone(): void
    {
        $this->imagick = clone $this->imagick;
    }
}
