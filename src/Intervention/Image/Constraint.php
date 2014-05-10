<?php

namespace Intervention\Image;

class Constraint
{
    const ASPECTRATIO = 1;
    const UPSIZE = 2;

    private $size;
    private $fixed = 0;

    public function __construct(Size $size) 
    {
        $this->size = $size;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function fix($type)
    {
        $this->fixed = ($this->fixed & ~(1 << $type)) | (1 << $type);
    }

    public function isFixed($type)
    {
        return (bool) ($this->fixed & (1 << $type));
    }

    public function aspectRatio()
    {
        $this->fix(self::ASPECTRATIO);
    }

    public function upsize()
    {
        $this->fix(self::UPSIZE);
    }
}
