<?php

namespace Intervention\Image\Interfaces;

interface ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface;
}
