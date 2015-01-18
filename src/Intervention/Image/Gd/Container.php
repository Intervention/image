<?php

namespace Intervention\Image\Gd;

use Intervention\Image\Animation;
use Intervention\Image\Frame;
use Intervention\Image\ContainerInterface;

class Container extends Animation implements ContainerInterface
{
    /**
     * Driver
     *
     * @var \Intervention\Image\Gd\Driver
     */
    protected $driver;

    /**
     * Return first image resource
     *
     * @return resource
     */
    public function getCore()
    {
        return $this->getFrames()[0]->getCore();
    }

    /**
     * Setup image stack with new resource
     *
     * @param resource $core
     * @return \Intervention\Image\Gd\Container
     */
    public function setCore($core)
    {
        $this->setFrames(array(
            new Frame($core)
        ));

        return $this;
    }

    /**
     * Return number of frames in container
     *
     * @return int
     */
    public function countFrames()
    {
        return count($this->getFrames());
    }

    /**
     * Add image source to Container
     *
     * @param mixed   $source
     * @param integer $delay
     * @return \Intervention\Image\Gd\Container
     */
    public function add($source, $delay = 0)
    {
        $this->addFrame(new Frame(
            $this->driver->init($source)->getCore(),
            $delay
        ));

        return $this;
    }

    /**
     * Attach driver current instance
     *
     * @param  Driver $driver
     * @return \Intervention\Image\Gd\Container
     */
    public function attachDriver(Driver $driver)
    {
        $this->driver = $driver;

        return $this;
    }
}
