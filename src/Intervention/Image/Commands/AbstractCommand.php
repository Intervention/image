<?php

namespace Intervention\Image\Commands;

use Intervention\Image\Commands\Argument;

abstract class AbstractCommand
{
    /**
     * Arguments of command
     *
     * @var array
     */
    public $arguments;

    /**
     * Output of command
     *
     * @var mixed
     */
    protected $output;

    /**
     * Executes current command on given image
     *
     * @param  \Intervention\Image\Image $image
     * @return mixed
     */
    abstract public function execute($image);

    /**
     * Creates new command instance
     *
     * @param array $arguments
     */
    public function __construct($arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * Creates new argument instance from given argument key
     *
     * @param  int $key
     * @return \Intervention\Image\Commands\Argument
     */
    final public function argument($key)
    {
        return new Argument($this, $key);
    }

    /**
     * Returns output data of current command
     *
     * @return mixed
     */
    final public function getOutput()
    {
        return $this->output ? $this->output : null;
    }

    /**
     * Determines if current instance has output data
     *
     * @return boolean
     */
    final public function hasOutput()
    {
        return ! is_null($this->output);
    }

    /**
     * Sets output data of current command
     *
     * @param mixed $value
     */
    final public function setOutput($value)
    {
        $this->output = $value;
    }
}
