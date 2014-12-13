<?php

namespace Intervention\Image;

class Frame
{
    const DISPOSAL_METHOD_LEAVE = 1;
    const DISPOSAL_METHOD_BACKGROUND = 2;
    const DISPOSAL_METHOD_PREVIOUS = 3;

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
     * Disposal method
     *
     * @var integer
     */
    public $disposal;

    /**
     * Create new frame instance
     *
     * @param mixed   $core
     * @param integer $delay
     * @param integer $disposal
     */
    public function __construct($core, $delay = 0, $disposal = DISPOSAL_METHOD_LEAVE)
    {
        $this->core = $core;
        $this->delay = $delay;
        $this->disposal = $disposal;
    }
}
