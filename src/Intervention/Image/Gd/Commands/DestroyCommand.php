<?php

namespace Intervention\Image\Gd\Commands;

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
        // destroy image cores
        foreach ($image as $frame) {
            imagedestroy($frame->getCore());
        }

        // destroy backups    
        foreach ($image->getBackups() as $backup) {
            imagedestroy($backup);
        }

        return true;
    }
}
