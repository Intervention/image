<?php

namespace Intervention\Image;

use IteratorAggregate;
use ArrayIterator;

class Animation implements IteratorAggregate
{
    /**
     * Time to loop through frames
     * (null means forever)
     *
     * @var mixed
     */
    public $loops;

    /**
     * Array of frames
     *
     * @var array
     */
    private $frames = array();

    /**
     * Create new instance of Animation
     *
     * @param integer $loops
     */
    public function __construct($loops = null) 
    {
        $this->loops = $loops;
    }

    /**
     * Returns Iterator
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->frames);
    }

    /**
     * Set the number of loops
     *
     * @param integer $value
     */
    public function setLoops($value)
    {
        $this->loops = $value;
    }

    /**
     * Return number of loops
     *
     * @return integer
     */
    public function getLoops()
    {
        return $this->loops;
    }

    /**
     * Add one frame to the Animation
     *
     * @param Frame $frame
     */
    public function addFrame(Frame $frame)
    {
        $this->frames[] = $frame;
    }

    /**
     * Append an array of frames to the Animation
     *
     * @param Array $frames
     */
    public function addFrames(Array $frames)
    {
        $this->frames = array_merge($this->frames, $frames);
    }

    /**
     * Overwrite the current frames with array of frames
     *
     * @param Array $frames
     */
    public function setFrames(Array $frames)
    {
        $this->frames = $frames;

        return $this;
    }

    /**
     * Get the current set of frames
     *
     * @return Array
     */
    public function getFrames()
    {
        return $this->frames;
    }
}
