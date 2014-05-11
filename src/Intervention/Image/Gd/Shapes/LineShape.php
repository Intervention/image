<?php

namespace Intervention\Image\Gd\Shapes;

use \Intervention\Image\Image;
use \Intervention\Image\Gd\Color;

class LineShape extends \Intervention\Image\AbstractShape
{
    public $x = 0;
    public $y = 0;
    public $color = '#000000';
    public $width = 1;

    function __construct($x = null, $y = null)
    {
        $this->x = is_numeric($x) ? intval($x) : $this->x;
        $this->y = is_numeric($y) ? intval($y) : $this->y;
    }

    public function color($color)
    {
        $this->color = $color;
    }

    public function width($width)
    {
        throw new \Intervention\Image\Exception\NotSupportedException(
            "Line width is not supported by GD driver."
        );
    }

    public function applyToImage(Image $image, $x = 0, $y = 0)
    {
        $color = new Color($this->color);
        imageline($image->getCore(), $x, $y, $this->x, $this->y, $color->getInt());

        return true;
    }
}
