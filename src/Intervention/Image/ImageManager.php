<?php

namespace Intervention\Image;

use Closure;
use Intervention\Image\Exception\MissingDependencyException;
use Intervention\Image\Exception\NotSupportedException;

class ImageManager
{
    /**
     * Config
     *
     * @var array
     */
    public $config = [
        'driver' => 'gd'
    ];

    /**
     * Creates new instance of Image Manager
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->checkRequirements();
        $this->configure($config);
    }

    /**
     * Overrides configuration settings
     *
     * @param array $config
     *
     * @return self
     */
    public function configure(array $config = [])
    {
        $this->config = array_replace($this->config, $config);

        return $this;
    }

    /**
     * Initiates an Image instance from different input types
     *
     * @param  mixed $data
     *
     * @return \Intervention\Image\Image
     */
    public function make($data)
    {
        return $this->createDriver()->init($data);
    }

    /**
     * Creates an empty image canvas
     *
     * @param  int   $width
     * @param  int   $height
     * @param  mixed $background
     *
     * @return \Intervention\Image\Image
     */
    public function canvas($width, $height, $background = null)
    {
        return $this->createDriver()->newImage($width, $height, $background);
    }

    /**
     * Create new cached image and run callback
     * (requires additional package intervention/imagecache)
     *
     * @param Closure $callback
     * @param int     $lifetime
     * @param boolean $returnObj
     *
     * @return Image
     */
    public function cache(Closure $callback, $lifetime = null, $returnObj = false)
    {
        if (class_exists('Intervention\\Image\\ImageCache')) {
            // create imagecache
            $imagecache = new ImageCache($this);

            // run callback
            if (is_callable($callback)) {
                $callback($imagecache);
            }

            return $imagecache->get($lifetime, $returnObj);
        }

        throw new MissingDependencyException(
            "Please install package intervention/imagecache before running this function."
        );
    }

    /**
     * Creates a driver instance according to config settings
     *
     * @return \Intervention\Image\AbstractDriver
     */
    private function createDriver()
    {
        if (is_string($this->config['driver'])) {
            $drivername = ucfirst($this->config['driver']);
            $driverclass = sprintf('Intervention\\Image\\%s\\Driver', $drivername);

            if (class_exists($driverclass)) {
                return new $driverclass;
            }

            throw new NotSupportedException(
                "Driver ({$drivername}) could not be instantiated."
            );
        }

        if ($this->config['driver'] instanceof AbstractDriver) {
            return $this->config['driver'];
        }

        throw new NotSupportedException(
            "Unknown driver type."
        );
    }

    /**
     * Check if all requirements are available
     *
     * @return void
     */
    private function checkRequirements(): void
    {
        if ( ! function_exists('finfo_buffer')) {
            throw new MissingDependencyException(
                "PHP Fileinfo extension must be installed/enabled to use Intervention Image."
            );
        }
    }
}
