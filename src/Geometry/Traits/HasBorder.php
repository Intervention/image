<?php

namespace Intervention\Image\Geometry\Traits;

trait HasBorder
{
    protected $borderColor = null;
    protected $borderSize = 0;

    public function border($color, int $size = 1): self
    {
        return $this->setBorderSize($size)->setBorderColor($color);
    }

    public function setBorderSize(int $size): self
    {
        $this->borderSize = $size;

        return $this;
    }

    public function getBorderSize(): int
    {
        return $this->borderSize;
    }

    public function setBorderColor($color): self
    {
        $this->borderColor = $color;

        return $this;
    }

    public function getBorderColor()
    {
        return $this->borderColor;
    }

    public function hasBorder(): bool
    {
        return $this->borderSize > 0 && !is_null($this->borderColor);
    }
}
