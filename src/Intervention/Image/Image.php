<?php

namespace Intervention\Image;

class Image extends File
{
    protected $driver;
    protected $core;
    protected $backup;
    public $encoded = '';

    public function __construct($driver = null, $core = null)
    {
        $this->driver = $driver;
        $this->core = $core;
    }

    public function __call($name, $arguments)
    {
        $command = $this->driver->executeCommand($this, $name, $arguments);
        return $command->hasOutput() ? $command->getOutput() : $this;
    }

    public function encode($format = null, $quality = 90)
    {
        return $this->driver->encode($this, $format, $quality);
    }

    public function save($path = null, $quality = null)
    {
        $path = is_null($path) ? ($this->dirname .'/'. $this->basename) : $path;
        $data = $this->encode(pathinfo($path, PATHINFO_EXTENSION), $quality);
        $saved = @file_put_contents($path, $data);

        if ($saved === false) {
            throw new Exception\NotWritableException(
                "Can't write image data to path ({$path})"
            );
        }

        // set new file info
        $this->setFileInfoFromPath($path);

        return $this;
    }

    public function filter(Filters\FilterInterface $filter)
    {
        return $filter->applyFilter($this);
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function setDriver($driver)
    {
        $this->driver = $driver;

        return $this;
    }

    public function getCore()
    {
        return $this->core;
    }

    public function setCore($core)
    {
        $this->core = $core;

        return $this;
    }

    public function getBackup()
    {
        return $this->backup;
    }

    public function setBackup($value)
    {
        $this->backup = $value;

        return $this;
    }

    public function isEncoded()
    {
        return ! is_null($this->encoded);
    }

    public function getEncoded()
    {
        return $this->encoded;
    }

    public function setEncoded($value)
    {
        $this->encoded = $value;

        return $this;
    }

    public function getWidth()
    {
        return $this->getSize()->width;
    }

    public function getHeight()
    {
        return $this->getSize()->height;
    }

    public function __toString()
    {
        return $this->encoded;
    }
}
