<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickException;
use ImagickPixel;
use Intervention\Image\Drivers\AbstractFrame;
use Intervention\Image\Exceptions\InputException;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class Frame extends AbstractFrame implements FrameInterface
{
    /**
     * Create new frame object
     *
     * @param Imagick $native
     * @throws ImagickException
     * @return void
     */
    public function __construct(protected Imagick $native)
    {
        $background = new ImagickPixel('rgba(255, 255, 255, 0)');
        $this->native->setImageBackgroundColor($background);
        $this->native->setBackgroundColor($background);
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::toImage()
     */
    public function toImage(DriverInterface $driver): ImageInterface
    {
        return new Image($driver, new Core($this->native()));
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::setNative()
     */
    public function setNative($native): FrameInterface
    {
        $this->native = $native;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::native()
     */
    public function native(): Imagick
    {
        return $this->native;
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::size()
     */
    public function size(): SizeInterface
    {
        return new Rectangle(
            $this->native->getImageWidth(),
            $this->native->getImageHeight()
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::delay()
     */
    public function delay(): float
    {
        return $this->native->getImageDelay() / 100;
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::setDelay()
     */
    public function setDelay(float $delay): FrameInterface
    {
        $this->native->setImageDelay(intval(round($delay * 100)));

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::dispose()
     */
    public function dispose(): int
    {
        return $this->native->getImageDispose();
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::setDispose()
     * @throws InputException
     */
    public function setDispose(int $dispose): FrameInterface
    {
        if (!in_array($dispose, [0, 1, 2, 3])) {
            throw new InputException('Value for argument $dispose must be 0, 1, 2 or 3.');
        }

        $this->native->setImageDispose($dispose);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::setOffset()
     */
    public function setOffset(int $left, int $top): FrameInterface
    {
        $this->native->setImagePage(
            $this->native->getImageWidth(),
            $this->native->getImageHeight(),
            $left,
            $top
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::offsetLeft()
     */
    public function offsetLeft(): int
    {
        return $this->native->getImagePage()['x'];
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::setOffsetLeft()
     */
    public function setOffsetLeft(int $offset): FrameInterface
    {
        return $this->setOffset($offset, $this->offsetTop());
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::offsetTop()
     */
    public function offsetTop(): int
    {
        return $this->native->getImagePage()['y'];
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::setOffsetTop()
     */
    public function setOffsetTop(int $offset): FrameInterface
    {
        return $this->setOffset($this->offsetLeft(), $offset);
    }
}
