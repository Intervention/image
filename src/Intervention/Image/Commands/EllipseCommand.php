<?php

namespace Intervention\Image\Commands;

use \Closure;

class EllipseCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Draws ellipse on given image
     *
     * @param  Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $x = $this->argument(0)->type('numeric')->required()->value();
        $y = $this->argument(1)->type('numeric')->required()->value();
        $width = $this->argument(2)->type('numeric')->value(10);
        $height = $this->argument(3)->type('numeric')->value(10);
        $callback = $this->argument(4)->type('closure')->value();

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
