<?php

namespace Intervention\Image\Commands;

use \Closure;

class EllipseCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $x = $this->getArgument(0);
        $y = $this->getArgument(1);
        $width = $this->getArgument(2);
        $height = $this->getArgument(3);
        $callback = $this->getArgument(4);

        $ellipse_classname = sprintf('\Intervention\Image\%s\Shapes\EllipseShape', 
            $image->getDriver()->getDriverName());

        $ellipse = new $ellipse_classname($width, $height);

        if ($callback instanceof Closure) {
            $callback($ellipse);
        }

        $ellipse->applyToImage($image, $x, $y);

        return true;
    }
}
