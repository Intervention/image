<?php

namespace Intervention\Image\Interfaces;

interface FactoryInterface
{
    public function newImage(int $width, int $height): ImageInterface;
}
