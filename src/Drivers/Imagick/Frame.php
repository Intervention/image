<?php

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class Frame implements FrameInterface
{
    public function __construct(protected Imagick $core)
    {
        //
    }

    public function setCore($core): FrameInterface
    {
        $this->core = $core;

        return $this;
    }

    public function getCore(): Imagick
    {
        return $this->core;
    }

    public function getSize(): SizeInterface
    {
        return new Rectangle(
            $this->core->getImageWidth(),
            $this->core->getImageHeight()
        );
    }

    public function getDelay(): float
    {
        return $this->core->getImageDelay() / 100;
    }

    public function setDelay(float $delay): FrameInterface
    {
        $this->core->setImageDelay(intval(round($delay * 100)));

        return $this;
    }

    public function getDispose(): int
    {
        return $this->core->getImageDispose();
    }

    public function setDispose(int $dispose): FrameInterface
    {
        $this->core->setImageDispose($dispose);

        return $this;
    }

    public function setOffset(int $left, int $top): FrameInterface
    {
        $this->core->setImagePage(
            $this->core->getImageWidth(),
            $this->core->getImageHeight(),
            $left,
            $top
        );

        return $this;
    }

    public function getOffsetLeft(): int
    {
        return $this->core->getImagePage()['x'];
    }

    public function setOffsetLeft(int $offset): FrameInterface
    {
        return $this->setOffset($offset, $this->getOffsetTop());
    }

    public function getOffsetTop(): int
    {
        return $this->core->getImagePage()['y'];
    }

    public function setOffsetTop(int $offset): FrameInterface
    {
        return $this->setOffset($this->getOffsetLeft(), $offset);
    }

    public function toImage(): ImageInterface
    {
        return new Image($this->getCore());
    }
}
