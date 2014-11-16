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
     * Move X coordinate
     *
     * @param integer $x
     */
    public function moveX($x)
    {
        $this->x = $this->x + intval($x);
    }

    /**
     * Move Y coordinate
     *
     * @param integer $y
     */
    public function moveY($y)
    {
        $this->y = $this->y + intval($y);
    }

    /**
     * Sets both X and Y coordinate
     *
     * @param  integer $x
     * @param  integer $y
     * @return Point
     */
    public function setPosition($x, $y)
    {
        $this->setX($x);
        $this->setY($y);

        return $this;
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
        $sin = round(sin(deg2rad($angle)), 6);
        $cos = round(cos(deg2rad($angle)), 6);

        $x = $cos * ($this->x - $pivot->x) - $sin * ($this->y - $pivot->y) + $pivot->x;
        $y = $sin * ($this->x - $pivot->x) + $cos * ($this->y - $pivot->y) + $pivot->y;

        return $this->setPosition($x, $y);
    }
}
