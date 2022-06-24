<?php

namespace Intervention\Image\Interfaces;

interface FactoryInterface
{
    public function newImage(int $width, int $height): ImageInterface;
    public function newCore(int $width, int $height);
}
