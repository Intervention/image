<?php

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickException;
use Intervention\Image\Drivers\Abstract\AbstractImage;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Iterator;

class Image extends AbstractImage implements ImageInterface, Iterator
{
    protected $iteratorIndex = 0;

    public function __construct(protected Imagick $imagick)
    {
        //
    }

    public function getImagick(): Imagick
    {
        return $this->imagick;
    }

    public function getFrame(int $key = 0): ?FrameInterface
    {
        try {
            $this->imagick->setIteratorIndex($key);
        } catch (ImagickException $e) {
            return null;
        }

        return new Frame($this->imagick->current());
    }

    public function addFrame(FrameInterface $frame): ImageInterface
    {
        $imagick = $frame->getCore();

        $imagick->setImageDelay($frame->getDelay());
        $imagick->setImageDispose($frame->getDispose());

        $size = $frame->getSize();
        $imagick->setImagePage(
            $size->getWidth(),
            $size->getHeight(),
            $frame->getOffsetLeft(),
            $frame->getOffsetTop()
        );

        $this->imagick->addImage($imagick);

        return $this;
    }

    public function setLoops(int $count): ImageInterface
    {
        $this->imagick->setImageIterations($count);

        return $this;
    }

    public function getLoops(): int
    {
        return $this->imagick->getImageIterations();
    }

    public function isAnimated(): bool
    {
        return $this->count() > 1;
    }

    public function count(): int
    {
        return $this->imagick->getNumberImages();
    }

    public function current()
    {
        $this->imagick->setIteratorIndex($this->iteratorIndex);

        return new Frame($this->imagick->current());
    }

    public function key()
    {
        return $this->iteratorIndex;
    }

    public function next(): void
    {
        $this->iteratorIndex = $this->iteratorIndex + 1;
    }

    public function rewind(): void
    {
        $this->iteratorIndex = 0;
    }

    public function valid(): bool
    {
        try {
            $result = $this->imagick->setIteratorIndex($this->iteratorIndex);
        } catch (ImagickException $e) {
            return false;
        }

        return $result;
    }

    public function getWidth(): int
    {
        return $this->getFrame()->getCore()->getImageWidth();
    }

    public function getHeight(): int
    {
        return $this->getFrame()->getCore()->getImageHeight();
    }

    public function pickColor(int $x, int $y, int $frame_key = 0): ?ColorInterface
    {
        if ($frame = $this->getFrame($frame_key)) {
            return new Color($frame->getCore()->getImagePixelColor($x, $y));
        }

        return null;
    }
}
