<?php

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class Frame implements FrameInterface
{
    public function __construct(protected Imagick $native)
    {
    }

    public function toImage(DriverInterface $driver): ImageInterface
    {
        return new Image($driver, new Core($this->native()));
    }

    public function setNative($native): FrameInterface
    {
        $this->native = $native;

        return $this;
    }

    public function native(): Imagick
    {
        return $this->native;
    }

    public function size(): SizeInterface
    {
        return new Rectangle(
            $this->native->getImageWidth(),
            $this->native->getImageHeight()
        );
    }

    public function delay(): float
    {
        return $this->native->getImageDelay() / 100;
    }

    public function setDelay(float $delay): FrameInterface
    {
        $this->native->setImageDelay(intval(round($delay * 100)));

        return $this;
    }

    public function dispose(): int
    {
        return $this->native->getImageDispose();
    }

    public function setDispose(int $dispose): FrameInterface
    {
        $this->native->setImageDispose($dispose);

        return $this;
    }

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

    public function offsetLeft(): int
    {
        return $this->native->getImagePage()['x'];
    }

    public function setOffsetLeft(int $offset): FrameInterface
    {
        return $this->setOffset($offset, $this->offsetTop());
    }

    public function offsetTop(): int
    {
        return $this->native->getImagePage()['y'];
    }

    public function setOffsetTop(int $offset): FrameInterface
    {
        return $this->setOffset($this->offsetLeft(), $offset);
    }
}
