<?php

namespace Intervention\Image\Gd\Shapes;

use \Intervention\Image\Image;
use \Intervention\Image\Gd\Color;

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
        $background = new Color($this->background);
        imagefilledellipse($image->getCore(), $x, $y, $this->width, $this->height, $background->getInt());

        if ($this->hasBorder()) {
            $border_color = new Color($this->border_color);
            imagesetthickness($image->getCore(), $this->border_width);
            imageellipse($image->getCore(), $x, $y, $this->width, $this->height, $border_color->getInt());
        }

        return true;
    }
}
