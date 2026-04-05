<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickException;
use ImagickPixel;
use ImagickPixelException;
use Intervention\Image\Drivers\AbstractFrame;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Size;

class Frame extends AbstractFrame implements FrameInterface
{
    /**
     * Create new frame.
     *
     * @throws DriverException
     */
    public function __construct(protected Imagick $native)
    {
        try {
            $background = new ImagickPixel('rgba(255, 255, 255, 0)');
            $this->native->setImageBackgroundColor($background);
            $this->native->setBackgroundColor($background);
        } catch (ImagickException | ImagickPixelException $e) {
            throw new DriverException('Failed to create instance of ' . self::class, previous: $e);
        }
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
     *
     * @throws InvalidArgumentException
     */
    public function setNative(mixed $native): FrameInterface
    {
        if (!$native instanceof Imagick) {
            throw new InvalidArgumentException(
                'Value for argument setNative() "$native" must be instanceof of ' . Imagick::class,
            );
        }

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
     *
     * @throws DriverException
     */
    public function size(): SizeInterface
    {
        try {
            return new Size(
                $this->native->getImageWidth(),
                $this->native->getImageHeight()
            );
        } catch (ImagickException | InvalidArgumentException $e) {
            throw new DriverException('Failed to get frame size', previous: $e);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::delay()
     *
     * @throws DriverException
     */
    public function delay(): float
    {
        try {
            return $this->native->getImageDelay() / 100;
        } catch (ImagickException $e) {
            throw new DriverException('Failed to get frame delay', previous: $e);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::setDelay()
     *
     * @throws DriverException
     */
    public function setDelay(float $delay): FrameInterface
    {
        try {
            $this->native->setImageDelay(intval(round($delay * 100)));
        } catch (ImagickException $e) {
            throw new DriverException('Failed to set frame disposal method', previous: $e);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::disposalMethod()
     *
     * @throws DriverException
     */
    public function disposalMethod(): int
    {
        try {
            return $this->native->getImageDispose();
        } catch (ImagickException $e) {
            throw new DriverException('Failed to get frame disposal method', previous: $e);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::setDisposalMethod()
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public function setDisposalMethod(int $method): FrameInterface
    {
        if (!in_array($method, [0, 1, 2, 3])) {
            throw new InvalidArgumentException('Value for argument disposal method "$method" must be 0, 1, 2 or 3');
        }

        try {
            $this->native->setImageDispose($method);
        } catch (ImagickException $e) {
            throw new DriverException('Failed to set frame disposal method', previous: $e);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::setOffset()
     *
     * @throws DriverException
     */
    public function setOffset(int $left, int $top): FrameInterface
    {
        try {
            $this->native->setImagePage(
                $this->native->getImageWidth(),
                $this->native->getImageHeight(),
                $left,
                $top
            );
        } catch (ImagickException $e) {
            throw new DriverException('Failed to set frame offset', previous: $e);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::offsetLeft()
     *
     * @throws DriverException
     */
    public function offsetLeft(): int
    {
        try {
            return $this->native->getImagePage()['x'];
        } catch (ImagickException $e) {
            throw new DriverException('Failed to get frame offset', previous: $e);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::setOffsetLeft()
     *
     * @throws RuntimeException
     */
    public function setOffsetLeft(int $offset): FrameInterface
    {
        return $this->setOffset($offset, $this->offsetTop());
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::offsetTop()
     *
     * @throws DriverException
     */
    public function offsetTop(): int
    {
        try {
            return $this->native->getImagePage()['y'];
        } catch (ImagickException $e) {
            throw new DriverException('Failed to get frame offset', previous: $e);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::setOffsetTop()
     *
     * @throws RuntimeException
     */
    public function setOffsetTop(int $offset): FrameInterface
    {
        return $this->setOffset($this->offsetLeft(), $offset);
    }
}
