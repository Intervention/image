<?php

namespace Intervention\Image;

abstract class AbstractShape
{
    public $background;
    public $border_color;
    public $border_width = 0;

    abstract public function applyToImage(Image $image, $posx = 0, $posy = 0);

    /**
     * Set text to be written
     *
     * @param  String $text
     * @return void
     */
    public function background($color)
    {
        $this->background = $color;
    }

    public function border($width, $color = null)
    {
        $this->border_width = is_numeric($width) ? intval($width) : 0;
        $this->border_color = is_null($color) ? '#000000' : $color;
    }

    public function hasBorder()
    {
        return ($this->border_width >= 1);
    }
}
