<?php

namespace Intervention\Image\Imagick\Commands;

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
            $frame->getCore()->negateImage(false);
        }
        
        return true;
    }
}
