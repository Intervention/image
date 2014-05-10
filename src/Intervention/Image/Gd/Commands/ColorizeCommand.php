<?php

namespace Intervention\Image\Gd\Commands;

class ColorizeCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $red = $this->getArgument(0);
        $green = $this->getArgument(1);
        $blue = $this->getArgument(2);

        // normalize colorize levels
        $red = round($red * 2.55);
        $green = round($green * 2.55);
        $blue = round($blue * 2.55);

        // apply filter
        return imagefilter($image->getCore(), IMG_FILTER_COLORIZE, $red, $green, $blue);
    }
}
