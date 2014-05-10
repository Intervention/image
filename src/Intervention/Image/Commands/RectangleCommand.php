<?php

namespace Intervention\Image\Commands;

use \Closure;

class RectangleCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $x1 = $this->getArgument(0);
        $y1 = $this->getArgument(1);
        $x2 = $this->getArgument(2);
        $y2 = $this->getArgument(3);
        $callback = $this->getArgument(4);

        $rectangle_classname = sprintf('\Intervention\Image\%s\Shapes\RectangleShape', 
            $image->getDriver()->getDriverName());

        $rectangle = new $rectangle_classname($x1, $y1, $x2, $y2);

        if ($callback instanceof Closure) {
            $callback($rectangle);
        }

        $rectangle->applyToImage($image, $x1, $y1);

        return true;
    }
}
