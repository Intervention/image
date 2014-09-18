<?php

namespace Intervention\Image;

class Image extends File
{
    /**
     * Instance of current image driver
     *
     * @var AbstractDriver
     */
    protected $driver;

    /**
     * Image resource/object of current image processor
     *
     * @var mixed
     */
    protected $core;

    /**
     * Array of Image resource backups of current image processor
     *
     * @var array
     */
    protected $backups = array();

    /**
     * Last image encoding result
     *
     * @var string
     */
    public $encoded = '';

    /**
     * Creates a new Image instance
     *
     * @param AbstractDriver $driver
     * @param mixed  $core
     */
    public function __construct(AbstractDriver $driver = null, $core = null)
    {
        $this->driver = $driver;
        $this->core = $core;
    }

    /**
     * Magic method to catch all image calls
     * usually any AbstractCommand
     *
     * @param  string $name
     * @param  Array  $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $command = $this->driver->executeCommand($this, $name, $arguments);
        return $command->hasOutput() ? $command->getOutput() : $this;
    }

    /**
     * Starts encoding of current image
     *
     * @param  string  $format
     * @param  integer $quality
     * @return \Intervention\Image\Image
     */
    public function encode($format = null, $quality = 90)
    {
        return $this->driver->encode($this, $format, $quality);
    }

    /**
     * Saves encoded image in filesystem
     *
     * @param  string  $path
     * @param  integer $quality
     * @return \Intervention\Image\Image
     */
    public function save($path = null, $quality = null)
    {
        $path = is_null($path) ? $this->basePath() : $path;

        if (is_null($path)) {
            throw new Exception\NotWritableException(
                "Can't write to undefined path."
            );
        }

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

    /**
     * Runs a given filter on current image
     *
     * @param  FiltersFilterInterface $filter
     * @return \Intervention\Image\Image
     */
    public function filter(Filters\FilterInterface $filter)
    {
        return $filter->applyFilter($this);
    }

    /**
     * Returns current image driver
     *
     * @return \Intervention\Image\AbstractDriver
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Sets current image driver
     * @param AbstractDriver $driver
     */
    public function setDriver(AbstractDriver $driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Returns current image resource/obj
     *
     * @return mixed
     */
    public function getCore()
    {
        return $this->core;
    }

    /**
     * Sets current image resource
     *
     * @param mixed $core
     */
    public function setCore($core)
    {
        $this->core = $core;

        return $this;
    }

    /**
     * Returns current image backup
     *
     * @param string $name
     * @return mixed
     */
    public function getBackup($name = null)
    {
        $name = is_null($name) ? 'default' : $name;

        if ( ! $this->backupExists($name)) {
            throw new \Intervention\Image\Exception\RuntimeException(
                "Backup with name ({$name}) not available. Call backup() before reset()."
            );
        }

        return $this->backups[$name];
    }

    /**
     * Sets current image backup
     *
     * @param mixed  $resource
     * @param string $name
     * @return self
     */
    public function setBackup($resource, $name = null)
    {
        $name = is_null($name) ? 'default' : $name;

        $this->backups[$name] = $resource;

        return $this;
    }

    /**
     * Checks if named backup exists
     *
     * @param  string $name
     * @return bool
     */
    private function backupExists($name)
    {
        return array_key_exists($name, $this->backups);
    }

    /**
     * Checks if current image is already encoded
     *
     * @return boolean
     */
    public function isEncoded()
    {
        return ! is_null($this->encoded);
    }

    /**
     * Returns encoded image data of current image
     *
     * @return string
     */
    public function getEncoded()
    {
        return $this->encoded;
    }

    /**
     * Sets encoded image buffer
     *
     * @param string $value
     */
    public function setEncoded($value)
    {
        $this->encoded = $value;

        return $this;
    }

    /**
     * Calculates current image width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->getSize()->width;
    }

    /**
     * Alias of getWidth()
     *
     * @return integer
     */
    public function width()
    {
        return $this->getWidth();
    }

    /**
     * Calculates current image height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->getSize()->height;
    }

    /**
     * Alias of getHeight
     *
     * @return integer
     */
    public function height()
    {
        return $this->getHeight();
    }

    /**
     * Reads mime type
     *
     * @return string
     */
    public function mime()
    {
        return $this->mime;
    }

    /**
     * Get fully qualified path to image
     *
     * @return string
     */
    public function basePath()
    {
        if ($this->dirname && $this->basename) {
            return ($this->dirname .'/'. $this->basename);
        }

        return null;
    }

    /**
     * Returns encoded image data in string conversion
     *
     * @return string
     */
    public function __toString()
    {
        return $this->encoded;
    }

    /**
     * Cloning an image
     */
    public function __clone()
    {
        $this->core = $this->driver->cloneCore($this->core);
    }
}
