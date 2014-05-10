<?php

namespace Intervention\Image;

class Point
{
    public $x;
    public $y;

    public function __construct($x = null, $y = null) 
    {
        $this->x = is_numeric($x) ? intval($x) : 0;
        $this->y = is_numeric($y) ? intval($y) : 0;
    }

    public function setX($x)
    {
        $this->x = intval($x);
    }

    public function setY($y)
    {
        $this->y = intval($y);
    }

    public function setPosition($x, $y)
    {
        $this->setX($x);
        $this->setY($y);
    }
}
