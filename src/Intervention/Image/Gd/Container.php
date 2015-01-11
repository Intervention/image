<?php

namespace Intervention\Image\Gd;

use Intervention\Image\Animation;
use Intervention\Image\Frame;
use Intervention\Image\ContainerInterface;

class Container extends Animation implements ContainerInterface
{
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
}
