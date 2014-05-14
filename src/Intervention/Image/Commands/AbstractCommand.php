<?php

namespace Intervention\Image\Commands;

abstract class AbstractCommand
{
    public $arguments;
    protected $output;

    abstract public function execute($image);

    public function __construct($arguments)
    {
        $this->arguments = $arguments;
    }

    public function argument($key)
    {
        return new \Intervention\Image\Commands\Argument($this, $key);
    }

    public function getOutput()
    {
        return $this->output ? $this->output : null;
    }

    public function hasOutput()
    {
        return ! is_null($this->output);
    }

    public function setOutput($value)
    {
        $this->output = $value;
    }
}
