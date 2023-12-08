<?php

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickException;
use Iterator;
use Intervention\Image\Interfaces\CoreInterface;
use Intervention\Image\Exceptions\AnimationException;
use Intervention\Image\Interfaces\FrameInterface;

class Core implements CoreInterface, Iterator
{
    protected int $iteratorIndex = 0;

    public function __construct(protected Imagick $imagick)
    {
    }

    public function add(FrameInterface $frame): CoreInterface
    {
        $imagick = $frame->native();

        $imagick->setImageDelay($frame->delay());
        $imagick->setImageDispose($frame->dispose());

        $size = $frame->size();
        $imagick->setImagePage(
            $size->width(),
            $size->height(),
            $frame->offsetLeft(),
            $frame->offsetTop()
        );

        $this->imagick->addImage($imagick);

        return $this;
    }

    public function count(): int
    {
        return $this->imagick->getNumberImages();
    }

    public function current(): mixed
    {
        $this->imagick->setIteratorIndex($this->iteratorIndex);

        return new Frame($this->imagick->current());
    }

    public function next(): void
    {
        $this->iteratorIndex = $this->iteratorIndex + 1;
    }

    public function key(): mixed
    {
        return $this->iteratorIndex;
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

    public function rewind(): void
    {
        $this->iteratorIndex = 0;
    }

    public function native(): mixed
    {
        return $this->imagick;
    }

    public function setNative(mixed $native): CoreInterface
    {
        $this->imagick = $native;

        return $this;
    }

    public function frame(int $position): FrameInterface
    {
        foreach ($this->imagick as $core) {
            if ($core->getIteratorIndex() == $position) {
                return new Frame($core);
            }
        }

        throw new AnimationException('Frame #' . $position . ' is not be found in the image.');
    }

    public function loops(): int
    {
        return $this->imagick->getImageIterations();
    }

    public function setLoops(int $loops): CoreInterface
    {
        $this->imagick = $this->imagick->coalesceImages();
        $this->imagick->setImageIterations($loops);

        return $this;
    }

    public function first(): FrameInterface
    {
        return $this->frame(0);
    }

    public function last(): FrameInterface
    {
        return $this->frame($this->count());
    }
}
