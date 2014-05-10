<?php

namespace Intervention\Image\Commands;

use \Closure;

class TextCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $text = $this->getArgument(0);
        $x = $this->getArgument(1, 0);
        $y = $this->getArgument(2, 0);
        $callback = $this->getArgument(3);

        $fontclassname = sprintf('\Intervention\Image\%s\Font', 
            $image->getDriver()->getDriverName());

        $font = new $fontclassname($text);

        if ($callback instanceof Closure) {
            $callback($font);
        }

        $font->applyToImage($image, $x, $y);

        return true;
    }
}
