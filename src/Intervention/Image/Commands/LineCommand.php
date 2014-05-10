<?php

namespace Intervention\Image\Commands;

use \Closure;

class LineCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $x1 = $this->getArgument(0);
        $y1 = $this->getArgument(1);
        $x2 = $this->getArgument(2);
        $y2 = $this->getArgument(3);
        $callback = $this->getArgument(4);

        $line_classname = sprintf('\Intervention\Image\%s\Shapes\LineShape', 
            $image->getDriver()->getDriverName());

        $line = new $line_classname($x2, $y2);

        if ($callback instanceof Closure) {
            $callback($line);
        }

        $line->applyToImage($image, $x1, $y1);

        return true;
    }
}
