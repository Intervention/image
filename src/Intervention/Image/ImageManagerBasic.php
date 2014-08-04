<?php

namespace Intervention\Image;

use Closure;

class ImageManagerBasic
{
    /**
     * Config
     *
     * @var array
     */
    protected $config;

    /**
     * Default config
     *
     * @var array
     */
    protected $defaultConfig = array(
        'driver' => 'gd',
    );

    /**
     * Creates new instance of Image Manager
     *
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        $this->config = $this->defaultConfig + $config;
    }

    /**
     * Creates a driver instance according to config settings
     *
     * @return Intervention\Image\AbstractDriver
     */
    private function createDriver()
    {
        $drivername = ucfirst($this->config['driver']);
        $driverclass = sprintf('Intervention\\Image\\%s\\Driver', $drivername);

        if (class_exists($driverclass)) {
            return new $driverclass;
        }

        throw new \Intervention\Image\Exception\NotSupportedException(
            "Driver ({$drivername}) could not be instantiated."
        );
    }

    /**
     * Initiates an Image instance from different input types
     *
     * @param  mixed $data
     *
     * @return Intervention\Image\Image
     */
    public function make($data)
    {
        return $this->createDriver()->init($data);
    }

    /**
     * Creates an empty image canvas
     *
     * @param  integer $width
     * @param  integer $height
     * @param  mixed $background
     *
     * @return Intervention\Image\Image
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
     * @param integer $lifetime
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

        throw new \Intervention\Image\Exception\NotSupportedException(
            "Please install package intervention/imagecache before running this function."
        );
    }
}
