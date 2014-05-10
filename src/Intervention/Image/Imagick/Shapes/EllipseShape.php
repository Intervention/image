<?php

namespace Intervention\Image\Imagick\Shapes;

use \Intervention\Image\Image;
use \Intervention\Image\Imagick\Color;

class EllipseShape extends \Intervention\Image\AbstractShape
{
    public $width = 100;
    public $height = 100;

    function __construct($width = null, $height = null) 
    {
        $this->width = is_numeric($width) ? intval($width) : $this->width;
        $this->height = is_numeric($height) ? intval($height) : $this->height;
    }   

    public function applyToImage(Image $image, $x = 0, $y = 0)
    {
        $circle = new \ImagickDraw;

        // set background
        $bgcolor = new Color($this->background);
        $circle->setFillColor($bgcolor->getPixel()); 

        // set border
        if ($this->hasBorder()) {
            $border_color = new Color($this->border_color);
            $circle->setStrokeWidth($this->border_width);
            $circle->setStrokeColor($border_color->getPixel());
        }

        $circle->ellipse($x, $y, $this->width, $this->height, 0, 360); 

        $image->getCore()->drawImage($circle);

        return true;
    }
}
