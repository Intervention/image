<?php

namespace Intervention\Image\Imagick\Commands;

class DestroyCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Destroys current image core and frees up memory
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        return $image->getCore()->clear();
    }
}
