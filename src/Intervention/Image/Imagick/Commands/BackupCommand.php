<?php

namespace Intervention\Image\Imagick\Commands;

class BackupCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Saves a backups of current state of image core
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        // clone current image resource
        $image->setBackup(clone $image->getCore());

        return true;
    }
}
