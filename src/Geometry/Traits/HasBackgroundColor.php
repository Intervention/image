<?php

namespace Intervention\Image\Geometry\Traits;

trait HasBackgroundColor
{
    protected $backgroundColor = null;

    public function background($color): self
    {
        return $this->setBackgroundColor($color);
    }

    public function setBackgroundColor($color): self
    {
        $this->backgroundColor = $color;

        return $this;
    }

    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    public function hasBackgroundColor(): bool
    {
        return !is_null($this->backgroundColor);
    }
}
