<?php

namespace Intervention\Image\Imagick\Commands;

class BackupCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        // clone current image resource
        $image->setBackup(clone $image->getCore());

        return true;
    }
}
