<?php

namespace Intervention\Image\Geometry\Traits;

trait HasBorder
{
    protected mixed $borderColor = null;
    protected int $borderSize = 0;

    public function setBorder(mixed $color, int $size = 1): self
    {
        return $this->setBorderSize($size)->setBorderColor($color);
    }

    public function setBorderSize(int $size): self
    {
        $this->borderSize = $size;

        return $this;
    }

    public function borderSize(): int
    {
        return $this->borderSize;
    }

    public function setBorderColor(mixed $color): self
    {
        $this->borderColor = $color;

        return $this;
    }

    public function borderColor(): mixed
    {
        return $this->borderColor;
    }

    public function hasBorder(): bool
    {
        return $this->borderSize > 0 && !is_null($this->borderColor);
    }
}
