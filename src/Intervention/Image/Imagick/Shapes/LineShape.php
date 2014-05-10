<?php

namespace Intervention\Image\Imagick\Shapes;

use \Intervention\Image\Image;
use \Intervention\Image\Imagick\Color;

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
        $line = new \ImagickDraw;

        $color = new Color($this->color);
        $line->setStrokeColor($color->getPixel());
        $line->setStrokeWidth($this->width);

        $line->line($this->x, $this->y, $x, $y);
        $image->getCore()->drawImage($line);

        return true;
    }
}
