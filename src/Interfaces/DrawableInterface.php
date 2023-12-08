<?php

namespace Intervention\Image\Interfaces;

interface DrawableInterface
{
    public function position(): PointInterface;
    public function setBackgroundColor(mixed $color);
    public function backgroundColor(): mixed;
    public function hasBackgroundColor(): bool;
    public function setBorder(mixed $color, int $size = 1);
    public function setBorderSize(int $size);
    public function setBorderColor(mixed $color);
    public function borderSize(): int;
    public function borderColor(): mixed;
    public function hasBorder(): bool;
}
