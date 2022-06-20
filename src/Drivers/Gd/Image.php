<?php

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Abstract\AbstractImage;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use IteratorAggregate;
use Traversable;

class Image extends AbstractImage implements ImageInterface, IteratorAggregate
{
    public function __construct(protected Collection $frames, protected int $loops = 0)
    {
        //
    }

    public function getIterator(): Traversable
    {
        return $this->frames;
    }

    public function count(): int
    {
        return $this->frames->count();
    }

    public function isAnimated(): bool
    {
        return $this->count() > 1;
    }

    public function getLoops(): int
    {
        return $this->loops;
    }

    public function setLoops(int $count): self
    {
        $this->loops = $count;

        return $this;
    }

    public function getFrame(int $key = 0): ?FrameInterface
    {
        return $this->frames->get($key);
    }

    public function addFrame(FrameInterface $frame): ImageInterface
    {
        $this->frames->push($frame);

        return $this;
    }

    public function getWidth(): int
    {
        return imagesx($this->getFrame()->getCore());
    }

    public function getHeight(): int
    {
        return imagesy($this->getFrame()->getCore());
    }

    public function pickColor(int $x, int $y, int $frame_key = 0): ?ColorInterface
    {
        if ($frame = $this->getFrame($frame_key)) {
            return new Color(imagecolorat($frame->getCore(), $x, $y));
        }

        return null;
    }
}
