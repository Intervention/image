<?php

namespace Intervention\Image;

use Illuminate\Config\Repository as Config;
use Illuminate\Config\FileLoader;
use Illuminate\Filesystem\Filesystem;

class ImageManager
{
    public $config;

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
