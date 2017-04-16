<?php

namespace Intervention\Image\Imagick\Commands;

class StripCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Strips the image of all profiles and comments
     *
     * @param  Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        return $image->getCore()->stripImage();
    }
}
