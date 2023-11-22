<?php

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Collection;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\CoreInterface;
use Intervention\Image\Interfaces\FrameInterface;

class Core extends Collection implements CoreInterface
{
    protected int $loops = 0;

    public function native()
    {
        return $this->first()->native();
    }

    public function width(): int
    {
        return imagesx($this->native());
    }

    public function height(): int
    {
        return imagesy($this->native());
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

    public function colorspace(): ColorspaceInterface
    {
        return new RgbColorspace();
    }
}
