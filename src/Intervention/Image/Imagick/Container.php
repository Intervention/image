<?php

namespace Intervention\Image\Imagick;

use Intervention\Image\ContainerInterface;
use Intervention\Image\Frame;
use \Iterator;

class Container implements ContainerInterface, Iterator
{
    private $imagick;
    private $position;

    public function __construct(\Imagick $imagick)
    {
        $this->imagick = $imagick;
    }

    public function getCore($index = null)
    {
        if (is_numeric($index)) {
            return $this->getImagickAtPosition($index);
        }
        
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

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        $imagick = $this->getImagickAtPosition();

        return new Frame(
            $imagick,
            $imagick->getImageDelay(),
            $imagick->getImageDispose()
        );
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return is_a($this->getImagickAtPosition(), 'Imagick');
    }

    private function getImagickAtPosition($position = null)
    {
        $position = is_numeric($position) ? $position : $this->position;

        foreach ($this->imagick as $key => $imagick) {
            if ($key == $position) {
                return $imagick;
            }
        }
    }
}
