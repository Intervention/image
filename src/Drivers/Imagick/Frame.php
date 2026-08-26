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
     * Position this frame was taken from, or null if it is not bound to a
     * position in a sequence.
     */
    protected ?int $position = null;

    /**
     * Create new frame.
     *
     * @throws DriverException
     */
    public function __construct(protected Imagick $native)
    {
        try {
            // Imagick::current() returns the wand itself, so a frame taken
            // from an animation shares the whole sequence with its core. Keep
            // the position it was taken from to be able to seek back to it.
            $this->position = $this->native->getIteratorIndex();

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
     *
     * @throws DriverException
     */
    public function toImage(DriverInterface $driver): ImageInterface
    {
        try {
            // The native of a frame taken from an animation still holds the
            // whole sequence, and any other frame access in the meantime has
            // moved its pointer. Seek back before copying the frame out,
            // otherwise the resulting image would report every frame and hold
            // whichever one the shared wand was left on.
            if ($this->position !== null) {
                $this->native->setIteratorIndex($this->position);
            }

            return new Image($driver, new Core($this->native->getImage()));
        } catch (ImagickException $e) {
            throw new DriverException('Failed to transform frame into image', previous: $e);
        }
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

        // the replacement carries its own sequence, so the position this
        // frame was taken from does not apply to it anymore
        $this->position = null;

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
                $this->native->getImageHeight(),
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
                $top,
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
