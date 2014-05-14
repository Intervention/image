<?php

namespace Intervention\Image\Commands;

use \Closure;

class TextCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $text = $this->argument(0)->required()->value();
        $x = $this->argument(1, 0)->type('numeric')->value();
        $y = $this->argument(2, 0)->type('numeric')->value();
        $callback = $this->argument(3)->type('closure')->value();

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
