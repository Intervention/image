<?php

namespace Intervention\Image\Gd;

use Intervention\Image\Animation;
use Intervention\Image\Frame;
use Intervention\Image\ContainerInterface;

class Container extends Animation implements ContainerInterface
{
    public $driver;

    public function __construct($driver = null)
    {
        $this->driver = $driver;
    }

    public function getCore()
    {
        return $this->getFrames()[0]->getCore();
    }

    public function setCore($core)
    {
        $this->setFrames(array(
            new Frame($core)
        ));
    }

    public function countFrames()
    {
        return count($this->getFrames());
    }

    public function add($source, $delay = 0)
    {
        $core = $this->driver->init($source)->getCore();

        $this->addFrame(new Frame(
            $core,
            $delay
        ));
    }
}
