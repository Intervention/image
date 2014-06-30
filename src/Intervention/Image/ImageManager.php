<?php

namespace Intervention\Image;

use Closure;
use Illuminate\Config\Repository as Config;
use Illuminate\Config\FileLoader;
use Illuminate\Filesystem\Filesystem;

class ImageManager
{
    /**
     * Instance of Illuminate Config respository
     *
     * @var \Illuminate\Config\Repository
     */
    public $config;

    /**
     * Creates new instance of Image Manager
     *
     * @param \Illuminate\Config\Repository $config
     */
    public function __construct(\Illuminate\Config\Repository $config = null)
    {
        // create configurator
        if (is_a($config, '\Illuminate\Config\Repository')) {

            $this->config = $config;

        } else {

            $config_path = __DIR__.'/../../config';
            $env = 'production';

            $file = new Filesystem;
            $loader = new FileLoader($file, $config_path);
            $this->config = new Config($loader, $env);
            $this->config->package('intervention/image', $config_path, 'image');
        }
    }

    /**
     * Creates a driver instance according to config settings
     *
     * @return Intervention\Image\AbstractDriver
     */
    private function createDriver()
    {
        $drivername = ucfirst($this->config->get('image::driver'));
        $driverclass = sprintf('\Intervention\Image\%s\Driver', $drivername);

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
        if (class_exists('\Intervention\Image\ImageCache')) {
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
