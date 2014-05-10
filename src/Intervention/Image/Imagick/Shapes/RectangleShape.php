<?php

namespace Intervention\Image\Imagick\Shapes;

use \Intervention\Image\Image;
use \Intervention\Image\Imagick\Color;

class RectangleShape extends \Intervention\Image\AbstractShape
{
    public $x1 = 0;
    public $y1 = 0;
    public $x2 = 0;
    public $y2 = 0;

    function __construct($x1 = null, $y1 = null, $x2 = null, $y2 = null) 
    {
        $this->x1 = is_numeric($x1) ? intval($x1) : $this->x1;
        $this->y1 = is_numeric($y1) ? intval($y1) : $this->y1;
        $this->x2 = is_numeric($x2) ? intval($x2) : $this->x2;
        $this->y2 = is_numeric($y2) ? intval($y2) : $this->y2;
    }   

    public function applyToImage(Image $image, $x = 0, $y = 0)
    {
        $rectangle = new \ImagickDraw;

        // set background
        $bgcolor = new Color($this->background);
        $rectangle->setFillColor($bgcolor->getPixel()); 

        // set border
        if ($this->hasBorder()) {
            $border_color = new Color($this->border_color);
            $rectangle->setStrokeWidth($this->border_width);
            $rectangle->setStrokeColor($border_color->getPixel());
        }

        $rectangle->rectangle($this->x1, $this->y1, $this->x2, $this->y2); 

        $image->getCore()->drawImage($rectangle);

        return true;
    }
}
