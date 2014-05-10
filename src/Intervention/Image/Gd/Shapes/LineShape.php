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
        $this->width = $width;
    }

    public function applyToImage(Image $image, $x = 0, $y = 0)
    {
        $angle = atan2(($this->y - $y),($this->x - $x)); 
        $dist_x=$this->width*(sin($angle));
        $dist_y=$this->width*(cos($angle));

        $points = array(
            ceil($this->x + $dist_x),
            ceil($this->y + $dist_y),
            ceil($x + $dist_x),
            ceil($y + $dist_y),
            ceil($x - $dist_x),
            ceil($y - $dist_y),
            ceil($this->x - $dist_x),
            ceil($this->y - $dist_y)
        );

        $color = new Color($this->color);
        imagefilledpolygon($image->getCore(), $points, (count($points)/2), $color->getInt());

        return true;
    }
}
