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
    public function __construct(protected Imagick $data)
    {
    }

    public function toImage(DriverInterface $driver): ImageInterface
    {
        return new Image($driver, new Core($this->data()));
    }

    public function setData($data): FrameInterface
    {
        $this->data = $data;

        return $this;
    }

    public function data(): Imagick
    {
        return $this->data;
    }

    public function size(): SizeInterface
    {
        return new Rectangle(
            $this->data->getImageWidth(),
            $this->data->getImageHeight()
        );
    }

    public function delay(): float
    {
        return $this->data->getImageDelay() / 100;
    }

    public function setDelay(float $delay): FrameInterface
    {
        $this->data->setImageDelay(intval(round($delay * 100)));

        return $this;
    }

    public function dispose(): int
    {
        return $this->data->getImageDispose();
    }

    public function setDispose(int $dispose): FrameInterface
    {
        $this->data->setImageDispose($dispose);

        return $this;
    }

    public function setOffset(int $left, int $top): FrameInterface
    {
        $this->data->setImagePage(
            $this->data->getImageWidth(),
            $this->data->getImageHeight(),
            $left,
            $top
        );

        return $this;
    }

    public function offsetLeft(): int
    {
        return $this->data->getImagePage()['x'];
    }

    public function setOffsetLeft(int $offset): FrameInterface
    {
        return $this->setOffset($offset, $this->offsetTop());
    }

    public function offsetTop(): int
    {
        return $this->data->getImagePage()['y'];
    }

    public function setOffsetTop(int $offset): FrameInterface
    {
        return $this->setOffset($this->offsetLeft(), $offset);
    }
}
