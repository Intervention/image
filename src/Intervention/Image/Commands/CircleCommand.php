<?php

namespace Intervention\Image\Commands;

use \Closure;

class CircleCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $x = $this->getArgument(0);
        $y = $this->getArgument(1);
        $radius = $this->getArgument(2);
        $callback = $this->getArgument(3);

        $circle_classname = sprintf('\Intervention\Image\%s\Shapes\CircleShape', 
            $image->getDriver()->getDriverName());

        $circle = new $circle_classname($radius);

        if ($callback instanceof Closure) {
            $callback($circle);
        }

        $circle->applyToImage($image, $x, $y);

        return true;
    }
}
