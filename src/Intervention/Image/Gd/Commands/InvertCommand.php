<?php

namespace Intervention\Image\Gd\Commands;

class InvertCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Inverts colors of an image
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        foreach ($image as $frame) {
            imagefilter($frame->getCore(), IMG_FILTER_NEGATE);
        }

        return true;
    }
}
