<?php

namespace Intervention\Image;

class Point
{
    /**
     * X coordinate
     *
     * @var integer
     */
    public $x;

    /**
     * Y coordinate
     *
     * @var integer
     */
    public $y;

    /**
     * Creates a new instance
     *
     * @param integer $x
     * @param integer $y
     */
    public function __construct($x = null, $y = null)
    {
        $this->x = is_numeric($x) ? intval($x) : 0;
        $this->y = is_numeric($y) ? intval($y) : 0;
    }

    /**
     * Sets X coordinate
     *
     * @param integer $x
     */
    public function setX($x)
    {
        $this->x = intval($x);
    }

    /**
     * Sets Y coordinate
     *
     * @param integer $y
     */
    public function setY($y)
    {
        $this->y = intval($y);
    }

    /**
     * Sets both X and Y coordinate
     *
     * @param integer $x
     * @param integer $y
     */
    public function setPosition($x, $y)
    {
        $this->setX($x);
        $this->setY($y);
    }

    /**
     * Rotate point ccw around pivot
     *
     * @param  float $angle
     * @param  Point $pivot
     * @return Point
     */
    public function rotate($angle, Point $pivot)
    {
        $sin = sin($angle);
        $cos = cos($angle);

        // translate point
        $this->x -= $pivot->x;
        $this->y -= $pivot->y;

        // rotate point clockwise
        // $x = $this->x * $cos - $this->y * $sin;
        // $y = $this->x * $sin + $this->y * $cos;

        // rotate point counter-clockwise
        $x = $this->x * $cos + $this->y * $sin;
        $y = ($this->x * -1) * $sin + $this->y * $cos;

        // translate point back
        $this->x = $x + $pivot->x;
        $this->y = $y + $pivot->y;
    
        return $this;
    }
}
