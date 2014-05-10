<?php

namespace Intervention\Image;

class ImageManager
{
    public $config;

    public function __construct(\Illuminate\Config\Repository $config = null)
    {
        // create configurator
        if (is_a($config, '\Illuminate\Config\Repository')) {

            $this->config = $config;

        } else {

            $loader = new FileLoader(new Filesystem, __DIR__.'/../../config');
            $this->config = new Config($loader, null);
        }
    }

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

    public function make($data)
    {
        return $this->createDriver()->init($data);
    }

    public function canvas($width, $height, $background = null)
    {
        return $this->createDriver()->newImage($width, $height, $background);
    }
}
