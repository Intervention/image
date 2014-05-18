<?php

namespace Intervention\Image\Gd\Shapes;

use \Intervention\Image\Image;
use \Intervention\Image\Gd\Color;

class EllipseShape extends \Intervention\Image\AbstractShape
{
    /**
     * Width of ellipse in pixels
     *
     * @var integer
     */
    public $width = 100;

    /**
     * Height of ellipse in pixels
     *
     * @var integer
     */
    public $height = 100;

    /**
     * Create new ellipse instance
     *
     * @param integer $width
     * @param integer $height
     */
    public function __construct($width = null, $height = null)
    {
        $this->width = is_numeric($width) ? intval($width) : $this->width;
        $this->height = is_numeric($height) ? intval($height) : $this->height;
    }

    /**
     * Draw ellipse instance on given image
     *
     * @param  Image   $image
     * @param  integer $x
     * @param  integer $y
     * @return boolean
     */
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
