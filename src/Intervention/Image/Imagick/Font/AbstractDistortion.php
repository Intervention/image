<?php

namespace Intervention\Image\Imagick\Font;

abstract class AbstractDistortion
{
    /**
     * @param $image \Imagick
     */
    abstract public function distort($image);
}