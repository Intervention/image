<?php

namespace Intervention\Image;

use Closure;

class ImageManager
{
    /**
     * Config
     *
     * @var array
     */
    public $config = array(
        'driver' => 'gd'
    );

    /**
     * Creates new instance of Image Manager
     *
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        $this->checkRequirements();
        $this->configure($config);
    }

    /**
     * Overrides configuration settings
     *
     * @param array $config
     */
    public function configure(array $config = array())
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
        $image = null;
        foreach ($this->createDriver() as $driver) {
            if($driver instanceof AbstractDriver) {
                $image = $driver->init($data);
            }
            if($image !== null) {
                break;
            }
        }
        return $image;
    }

    /**
     * Creates an empty image canvas
     *
     * @param  integer $width
     * @param  integer $height
     * @param  mixed $background
     *
     * @return \Intervention\Image\Image
     */
    public function canvas($width, $height, $background = null)
    {
        $image = null;
        foreach ($this->createDriver() as $driver) {
            $image = $driver->newImage($width, $height, $background);
            if($image !== null) {
                break;
            }
        }
        return $image;
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

        throw new \Intervention\Image\Exception\MissingDependencyException(
            "Please install package intervention/imagecache before running this function."
        );
    }

    /**
     * Creates a driver instance according to config settings
     *
     * @return array of \Intervention\Image\AbstractDriver
     */
    private function createDriver()
    {
        $drivers = array();
        $drivername = array();
        $driverclass = array();

        if (is_array($this->config['driver'])) {
            foreach ($this->config['driver'] as $i => $driver) {
                $drivername[$i] = ucfirst($driver);
                $driverclass[$i] = sprintf('Intervention\\Image\\%s\\Driver', $drivername[$i]);
            }
        } else {
            $drivername[0] = ucfirst($this->config['driver']);
            $driverclass[0] = sprintf('Intervention\\Image\\%s\\Driver', $drivername[0]);
        }

        foreach($driverclass as $i => $driverklass) {
            if (class_exists($driverklass)) {
                try {
                    $drivers[$i] = new $driverklass;
                } catch (Exception\NotSupportedException $e) {
                    $e_message[$i] = $e->getMessage();
                }
            } else {
                $e_message[$i] = "Driver ({$drivername[$i]}) could not be instantiated.";
            }
        }

        if(count($drivers) == 0) {
            throw new \Intervention\Image\Exception\NotSupportedException(
                implode(" ", $e_message)
            );
        }

        return $drivers;
    }

    /**
     * Check if all requirements are available
     *
     * @return void
     */
    private function checkRequirements()
    {
        if ( ! function_exists('finfo_buffer')) {
            throw new \Intervention\Image\Exception\MissingDependencyException(
                "PHP Fileinfo extension must be installed/enabled to use Intervention Image."
            );
        }
    }
}
