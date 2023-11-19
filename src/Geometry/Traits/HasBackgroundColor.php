<?php

namespace Intervention\Image\Geometry\Traits;

trait HasBackgroundColor
{
    protected mixed $backgroundColor = null;

    public function setBackgroundColor(mixed $color): self
    {
        $this->backgroundColor = $color;

        return $this;
    }

    public function backgroundColor(): mixed
    {
        return $this->backgroundColor;
    }

    public function hasBackgroundColor(): bool
    {
        return !empty($this->backgroundColor);
    }
}
