<?php

namespace Intervention\Image\Imagick\Shapes;

use \Intervention\Image\Image;

class CircleShape extends EllipseShape
{
    public $radius = 100;

    function __construct($radius = null) 
    {
        $this->width = is_numeric($radius) ? intval($radius) : $this->radius;
        $this->height = is_numeric($radius) ? intval($radius) : $this->radius;
        $this->radius = is_numeric($radius) ? intval($radius) : $this->radius;
    }   

    public function applyToImage(Image $image, $x = 0, $y = 0)
    {
        return parent::applyToImage($image, $x, $y);
    }
}
