<?php

namespace Intervention\Image\Drivers\Gd;

use GdImage;
use Intervention\Image\Collection;
use Intervention\Image\Drivers\Abstract\AbstractFrame;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;

class Frame extends AbstractFrame implements FrameInterface
{
    /**
     * Delay time in seconds after next frame is shown
     *
     * @var float
     */
    protected $delay = 0;

    /**
     * Disposal method of frame
     *
     * @var integer
     */
    protected $dispose = 1;

    /**
     * Left offset in pixel
     *
     * @var integer
     */
    protected $offset_left = 0;

    /**
     * Top offset in pixel
     *
     * @var integer
     */
    protected $offset_top = 0;

    public function __construct(protected GdImage $core)
    {
        //
    }

    public function getCore(): GdImage
    {
        return $this->core;
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
