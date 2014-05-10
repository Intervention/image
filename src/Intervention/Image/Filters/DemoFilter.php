<?php

namespace Intervention\Image\Filters;

class DemoFilter implements FilterInterface
{
    const DEFAULT_SIZE = 10;

    private $size;

    public function __construct($size = null)
    {
        $this->size = is_numeric($size) ? intval($size) : self::DEFAULT_SIZE;
    }

    public function applyFilter(\Intervention\Image\Image $image)
    {
        $image->pixelate($this->size);
        $image->greyscale();

        return $image;
    }
}
