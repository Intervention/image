<?php

namespace Intervention\Image\Typography;

use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Geometry\Point;

class Line
{
    protected $position;

    public function __construct(protected string $text)
    {
        $this->position = new Point();
    }

    public function getPosition(): Point
    {
        return $this->position;
    }

    public function setPosition(Point $point): self
    {
        $this->position = $point;

        return $this;
    }

    public function widthInFont(FontInterface $font): int
    {
        return $font->getBoxSize($this->text)->getWidth();
    }

    public function __toString(): string
    {
        return $this->text;
    }
}
