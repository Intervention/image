<?php

namespace Intervention\Image;

class Frame
{
    /**
     * Image data
     *
     * @var mixed
     */
    public $core;

    /**
     * Delay time in miliseconds after next frame is shown
     *
     * @var integer
     */
    public $delay;

    /**
     * Create new frame instance
     *
     * @param mixed   $core
     * @param integer $delay
     */
    public function __construct($core, $delay = 0)
    {
        $this->core = $core;
        $this->delay = $delay;
    }
}
