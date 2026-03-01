<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickException;
use Intervention\Image\Collection;
use Intervention\Image\Exceptions\DriverException;
use Iterator;
use Intervention\Image\Interfaces\CoreInterface;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\CollectionInterface;
use Intervention\Image\Interfaces\FrameInterface;

/**
 * @implements Iterator<FrameInterface>
 */
class Core implements CoreInterface, Iterator
{
    protected int $iteratorIndex = 0;
    protected CollectionInterface $meta;

    /**
     * Create new core instance
     *
     * @return void
     */
    public function __construct(protected Imagick $imagick)
    {
        $this->meta = new Collection();
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::has()
     */
    public function has(int|string $key): bool
    {
        try {
            return $this->imagick->setIteratorIndex((int) $key);
        } catch (ImagickException) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::push()
     *
     * @throws DriverException
     */
    public function push(mixed $item): CollectionInterface
    {
        return $this->add($item);
    }

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::map()
     */
    public function map(callable $callback): CoreInterface
    {
        throw new \Exception('Not implemented');
    }

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::filter()
     */
    public function filter(callable $callback): CoreInterface
    {
        throw new \Exception('Not implemented');
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::get()
     *
     * @throws DriverException
     */
    public function get(int|string $key, mixed $default = null): mixed
    {
        try {
            $this->imagick->setIteratorIndex((int) $key);
        } catch (ImagickException) {
            return $default;
        }

        try {
            return new Frame($this->imagick->current());
        } catch (ImagickException $e) {
            throw new DriverException('Failed to get current frame data', previous: $e);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::set()
     *
     * @throws DriverException
     */
    public function set(int|string $key, mixed $item): CollectionInterface
    {
        return $this->add($item);
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::at()
     *
     * @throws DriverException
     */
    public function at(int $key = 0, mixed $default = null): mixed
    {
        return $this->get($key, $default);
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::clear()
     */
    public function clear(): CollectionInterface
    {
        $this->imagick->clear();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::slice()
     *
     * @throws DriverException
     */
    public function slice(int $offset, ?int $length = null): CollectionInterface
    {
        $allowedIndexes = [];
        $length = is_null($length) ? $this->count() : $length;
        for ($i = $offset; $i < $offset + $length; $i++) {
            $allowedIndexes[] = $i;
        }

        $sliced = new Imagick();
        foreach ($this->imagick as $key => $native) {
            if (in_array($key, $allowedIndexes)) {
                try {
                    $sliced->addImage($native->getImage());
                } catch (ImagickException $e) {
                    throw new DriverException('Failed to slice image', previous: $e);
                }
            }
        }

        try {
            $sliced = $sliced->coalesceImages();
            $sliced->setImageIterations($this->imagick->getImageIterations());
        } catch (ImagickException $e) {
            throw new DriverException('Failed to slice image', previous: $e);
        }

        $this->imagick = $sliced;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::add()
     *
     * @throws DriverException
     */
    public function add(FrameInterface $frame): CoreInterface
    {
        $imagick = $frame->native();

        try {
            $imagick->setImageDelay(
                (int) round($frame->delay() * 100)
            );

            $imagick->setImageDispose($frame->disposalMethod());

            $size = $frame->size();
            $imagick->setImagePage(
                $size->width(),
                $size->height(),
                $frame->offsetLeft(),
                $frame->offsetTop()
            );

            $this->imagick->addImage($imagick);
        } catch (ImagickException $e) {
            throw new DriverException('Failed to add image frame', previous: $e);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::count()
     *
     * @throws DriverException
     */
    public function count(): int
    {
        try {
            return $this->imagick->getNumberImages();
        } catch (ImagickException $e) {
            throw new DriverException('Failed to count image frames', previous: $e);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see Iterator::rewind()
     *
     * @throws DriverException
     */
    public function current(): mixed
    {
        try {
            $this->imagick->setIteratorIndex($this->iteratorIndex);

            return new Frame($this->imagick->current());
        } catch (ImagickException $e) {
            throw new DriverException('Failed to iterate image frames', previous: $e);
        }
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
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public function frame(int $position): FrameInterface
    {
        foreach ($this->imagick as $core) {
            try {
                if ($core->getIteratorIndex() === $position) {
                    return new Frame($core);
                }
            } catch (ImagickException $e) {
                throw new DriverException('Failed to load image frame a position ' . $position, previous: $e);
            }
        }

        throw new InvalidArgumentException('Frame #' . $position . ' could not be found in the image');
    }

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::loops()
     *
     * @throws DriverException
     */
    public function loops(): int
    {
        try {
            return $this->imagick->getImageIterations();
        } catch (ImagickException $e) {
            throw new DriverException('Failed to get image loop count', previous: $e);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::setLoops()
     *
     * @throws DriverException
     */
    public function setLoops(int $loops): CoreInterface
    {
        try {
            $this->imagick = $this->imagick->coalesceImages();
            $this->imagick->setImageIterations($loops);
        } catch (ImagickException $e) {
            throw new DriverException('Failed to set image loop count', previous: $e);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::first()
     *
     * @throws DriverException
     * @throws StateException
     */
    public function first(): FrameInterface
    {
        try {
            return $this->frame(0);
        } catch (InvalidArgumentException $e) {
            throw new StateException('First frame not found in image', previous: $e);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectableInterface::last()
     *
     * @throws DriverException
     * @throws StateException
     */
    public function last(): FrameInterface
    {
        try {
            return $this->frame($this->count() - 1);
        } catch (InvalidArgumentException $e) {
            throw new StateException('Last frame not found in image', previous: $e);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see CoreInterface::meta()
     */
    public function meta(): CollectionInterface
    {
        return $this->meta;
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
        $this->meta = clone $this->meta;
        $this->imagick = clone $this->imagick;
    }
}
