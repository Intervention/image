<?php

namespace Intervention\Image\Gd\Shapes;

use \Intervention\Image\Image;

class CircleShape extends EllipseShape
{
    /**
     * Radius of circle in pixels
     *
     * @var integer
     */
    public $radius = 100;

    /**
     * Create new instance of circle
     *
     * @param integer $radius
     */
    public function __construct($radius = null)
    {
        $this->width = is_numeric($radius) ? intval($radius) : $this->radius;
        $this->height = is_numeric($radius) ? intval($radius) : $this->radius;
        $this->radius = is_numeric($radius) ? intval($radius) : $this->radius;
    }

    /**
     * Draw current circle on given image
     *
     * @param  Image   $image
     * @param  integer $x
     * @param  integer $y
     * @return boolean
     */
    public function applyToImage(Image $image, $x = 0, $y = 0)
    {
        return parent::applyToImage($image, $x, $y);
    }
}
