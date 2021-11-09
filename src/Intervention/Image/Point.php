<?php

namespace Intervention\Image;

class Point
{
    /**
     * X coordinate
     *
     * @var int
     */
    public $x;

    /**
     * Y coordinate
     *
     * @var int
     */
    public $y;

    /**
     * Creates a new instance
     *
     * @param int $x
     * @param int $y
     */
    public function __construct($x = null, $y = null)
    {
        $this->x = is_numeric($x) ? intval($x) : 0;
        $this->y = is_numeric($y) ? intval($y) : 0;
    }

    /**
     * Sets X coordinate
     *
     * @param  int $x
     * @return static
     */
    public function setX($x)
    {
        $this->x = intval($x);

        return $this;
    }

    /**
     * Sets Y coordinate
     *
     * @param  int $y
     * @return static
     */
    public function setY($y)
    {
        $this->y = intval($y);

        return $this;
    }

    /**
     * Sets both X and Y coordinate
     *
     * @param  int $x
     * @param  int $y
     * @return static
     */
    public function setPosition($x, $y)
    {
        $this->setX($x);
        $this->setY($y);

        return $this;
    }
}
