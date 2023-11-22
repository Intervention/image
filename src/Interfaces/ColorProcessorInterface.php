<?php

namespace Intervention\Image\Interfaces;

interface ColorProcessorInterface
{
    public function colorToNative(ColorInterface $color);
    public function nativeToColor(mixed $native): ColorInterface;
}
