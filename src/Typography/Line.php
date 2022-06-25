<?php

namespace Intervention\Image\Typography;

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
}
