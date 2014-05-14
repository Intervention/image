<?php

namespace Intervention\Image\Gd\Commands;

class PixelateCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $size = $this->argument(0)->type('integer')->value(10);

        return imagefilter($image->getCore(), IMG_FILTER_PIXELATE, $size, true);
    }
}
