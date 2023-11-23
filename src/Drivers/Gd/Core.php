<?php

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Collection;
use Intervention\Image\Interfaces\CoreInterface;
use Intervention\Image\Interfaces\FrameInterface;

class Core extends Collection implements CoreInterface
{
    protected int $loops = 0;

    public function native(): mixed
    {
        return $this->first()->native();
    }

    public function setNative(mixed $native): self
    {
        return $this->empty()->push(new Frame($native));
    }

    public function frame(int $position): FrameInterface
    {
        return $this->getAtPosition($position);
    }

    public function loops(): int
    {
        return $this->loops;
    }

    public function setLoops(int $loops): self
    {
        $this->loops = $loops;

        return $this;
    }
}
