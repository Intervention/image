<?php

namespace Intervention\Image;

use Intervention\Image\Interfaces\ResolutionInterface;

class Resolution implements ResolutionInterface
{
    public function __construct(protected float $x, protected float $y)
    {
        //
    }

    public function x(): float
    {
        return $this->x;
    }

    public function y(): float
    {
        return $this->y;
    }
}
