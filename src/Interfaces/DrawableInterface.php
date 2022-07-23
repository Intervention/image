<?php

namespace Intervention\Image\Interfaces;

interface DrawableInterface
{
    public function setBackgroundColor($color);
    public function getBackgroundColor();
    public function hasBackgroundColor();
    public function background($color);
    public function border($color, int $size = 1);
    public function setBorderSize(int $size);
    public function getBorderSize();
    public function setBorderColor($color);
    public function getBorderColor();
    public function hasBorder();
}
