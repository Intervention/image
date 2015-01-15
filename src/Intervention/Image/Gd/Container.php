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
     */
    public function setCore($core)
    {
        $this->setFrames(array(
            new Frame($core)
        ));
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
}
