<?php

namespace Intervention\Image\Gd\Commands;

class GreyscaleCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        return imagefilter($image->getCore(), IMG_FILTER_GRAYSCALE);
    }
}
