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

    public function getArgument($key, $default = null)
    {
        // return new \Intervention\Image\Argument($this->arguments, $key);

        if (is_array($this->arguments)) {
            return array_key_exists($key, $this->arguments) ? $this->arguments[$key] : $default;
        }

        return $default;
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
