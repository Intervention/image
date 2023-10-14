<?php

namespace Intervention\Image\Interfaces;

interface ColorspaceInterface
{
    /**
     * Convert given color to the format of the current colorspace
     *
     * @param  ColorInterface $color
     * @return ColorInterface
     */
    public function convertColor(ColorInterface $color): ColorInterface;
}
