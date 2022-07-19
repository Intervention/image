<?php

namespace Intervention\Image\Drivers\Gd;

use GdImage;
use Intervention\Image\Collection;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class Frame implements FrameInterface
{
    public function __construct(
        protected GdImage $core,
        protected float $delay = 0,
        protected int $dispose = 1,
        protected int $offset_left = 0,
        protected int $offset_top = 0
    ) {
        //
    }

    public function setCore($core): FrameInterface
    {
        $this->core = $core;

        return $this;
    }

    public function getCore(): GdImage
    {
        return $this->core;
    }

    public function unsetCore(): void
    {
        unset($this->core);
    }

    public function getSize(): SizeInterface
    {
        return new Rectangle(imagesx($this->core), imagesy($this->core));
    }

    public function getDelay(): float
    {
        return $this->delay;
    }

    public function setDelay(float $delay): FrameInterface
    {
        $this->delay = $delay;

        return $this;
    }

    public function getDispose(): int
    {
        return $this->dispose;
    }

    public function setDispose(int $dispose): FrameInterface
    {
        $this->dispose = $dispose;

        return $this;
    }

    public function setOffset(int $left, int $top): FrameInterface
    {
        $this->offset_left = $left;
        $this->offset_top = $top;

        return $this;
    }

    public function getOffsetLeft(): int
    {
        return $this->offset_left;
    }

    public function setOffsetLeft(int $offset): FrameInterface
    {
        $this->offset_left = $offset;

        return $this;
    }

    public function getOffsetTop(): int
    {
        return $this->offset_top;
    }

    public function setOffsetTop(int $offset): FrameInterface
    {
        $this->offset_top = $offset;

        return $this;
    }

    public function toImage(): ImageInterface
    {
        return new Image(new Collection([$this]));
    }
}
