<?php

namespace Intervention\Image\Gd\Commands;

class GreyscaleCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Turns an image into a greyscale version
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        foreach ($image as $frame) {
            imagefilter($frame->getCore(), IMG_FILTER_GRAYSCALE);
        }

        return true;
    }
}
