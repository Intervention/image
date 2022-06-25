<?php

namespace Intervention\Image\Typography;

use Intervention\Image\Interfaces\FontInterface;

class Line
{
    public function __construct(protected string $text)
    {
        //
    }

    public function __toString(): string
    {
        return $this->text;
    }

    public function width(FontInterface $font): int
    {
        return $font->getBoxSize($this->text)->width();
    }
}
