<?php

namespace Intervention\Image\Interfaces;

interface ColorProcessorInterface
{
    public function colorToNative(ColorInterface $color);
}
