<?php

namespace Intervention\Image\Gd;

use Intervention\Image\Animation;
use Intervention\Image\Frame;
use Intervention\Image\ContainerInterface;

class Container extends Animation implements ContainerInterface
{
    /**
     * Return first image resource
     *
     * @param interger $index
     * @return resource
     */
    public function getCore($index = null)
    {
        $index = is_numeric($index) ? $index : 0;
        $frames = $this->getFrames();

        if (array_key_exists($index, $frames)) {
            return $frames[$index]->getCore();
        }

        throw new \Intervention\Image\Exception\NotFoundException(
            "Animation has no index with number {$index}."
        );
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
        $driver = new Driver;

        $this->addFrame(new Frame(
            $driver->init($source)->getCore(),
            $delay
        ));

        return $this;
    }
}
