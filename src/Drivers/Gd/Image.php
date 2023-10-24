<?php

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Collection;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Drivers\Abstract\AbstractImage;
use Intervention\Image\Drivers\Gd\Traits\CanHandleColors;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use IteratorAggregate;
use Traversable;

class Image extends AbstractImage implements ImageInterface, IteratorAggregate
{
    use CanHandleColors;

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

    public function getFrame(int $position = 0): ?FrameInterface
    {
        return $this->frames->get($position);
    }

    public function addFrame(FrameInterface $frame): ImageInterface
    {
        $this->frames->push($frame);

        return $this;
    }

    public function width(): int
    {
        return imagesx($this->getFrame()->getCore());
    }

    public function height(): int
    {
        return imagesy($this->getFrame()->getCore());
    }

    public function pickColor(int $x, int $y, int $frame_key = 0): ?ColorInterface
    {
        if ($frame = $this->getFrame($frame_key)) {
            return $this->integerToColor(
                imagecolorat($frame->getCore(), $x, $y)
            );
        }

        return null;
    }

    public function getColorspace(): ColorspaceInterface
    {
        return new RgbColorspace();
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setColorspace()
     */
    public function setColorspace(string|ColorspaceInterface $target): ImageInterface
    {
        if (is_string($target) && !in_array($target, ['rgb', RgbColorspace::class])) {
            throw new NotSupportedException('Only RGB colorspace is supported with GD driver.');
        }

        if (is_object($target) && !is_a($target, RgbColorspace::class)) {
            throw new NotSupportedException('Only RGB colorspace is supported with GD driver.');
        }

        return $this;
    }
}
