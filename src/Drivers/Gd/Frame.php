<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd;

use GdImage;
use Intervention\Image\Drivers\AbstractFrame;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Size;

class Frame extends AbstractFrame implements FrameInterface
{
    /**
     * Create new frame instance
     *
     * @return void
     */
    public function __construct(
        protected GdImage $native,
        protected float $delay = 0,
        protected int $disposalMethod = 1,
        protected int $offsetLeft = 0,
        protected int $offsetTop = 0
    ) {
        //
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::toImage()
     */
    public function toImage(DriverInterface $driver): ImageInterface
    {
        return Image::usingDriver($driver)->setCore(new Core([$this]));
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::setNative()
     */
    public function setNative(mixed $native): FrameInterface
    {
        $this->native = $native;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::native()
     */
    public function native(): GdImage
    {
        return $this->native;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::size()
     *
     * @throws InvalidArgumentException
     */
    public function size(): SizeInterface
    {
        return new Size(imagesx($this->native), imagesy($this->native));
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::delay()
     */
    public function delay(): float
    {
        return $this->delay;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::setDelay()
     */
    public function setDelay(float $delay): FrameInterface
    {
        $this->delay = $delay;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::disposalMethod()
     */
    public function disposalMethod(): int
    {
        return $this->disposalMethod;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::setDisposalMethod()
     *
     * @throws InvalidArgumentException
     */
    public function setDisposalMethod(int $method): FrameInterface
    {
        if (!in_array($method, [0, 1, 2, 3])) {
            throw new InvalidArgumentException('Value for disposal method "$method" must be 0, 1, 2 or 3');
        }

        $this->disposalMethod = $method;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::setOffset()
     */
    public function setOffset(int $left, int $top): FrameInterface
    {
        $this->offsetLeft = $left;
        $this->offsetTop = $top;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::offsetLeft()
     */
    public function offsetLeft(): int
    {
        return $this->offsetLeft;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::setOffsetLeft()
     */
    public function setOffsetLeft(int $offset): FrameInterface
    {
        $this->offsetLeft = $offset;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::offsetTop()
     */
    public function offsetTop(): int
    {
        return $this->offsetTop;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::setOffsetTop()
     */
    public function setOffsetTop(int $offset): FrameInterface
    {
        $this->offsetTop = $offset;

        return $this;
    }

    /**
     * This workaround helps cloning GdImages which is currently not possible.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public function __clone(): void
    {
        $this->native = Cloner::clone($this->native);
    }
}
