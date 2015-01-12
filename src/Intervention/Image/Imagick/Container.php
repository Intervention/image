<?php

namespace Intervention\Image\Imagick;

use Intervention\Image\ContainerInterface;
use \IteratorAggregate;

class Container implements ContainerInterface, IteratorAggregate
{
    private $imagick;

    public function __construct(\Imagick $imagick)
    {
        $this->imagick = $imagick;
    }

    public function getIterator()
    {
        return $this->imagick;
    }

    public function getCore()
    {
        return $this->imagick;
    }

    public function setCore($core)
    {
        $this->imagick = $core;
    }

    public function countFrames()
    {
        return $this->imagick->getNumberImages();
    }
}
