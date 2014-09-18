<?php

namespace Intervention\Image\Gd\Commands;

class ResetCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Resets given image to its backup state
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $backupName = $this->argument(0)->value();

        if (is_resource($backup = $image->getBackup($backupName))) {

            // destroy old resource
            imagedestroy($image->getCore());
            // reset to new resource
            $image->setCore($backup);

            return true;
        }

        throw new \Intervention\Image\Exception\RuntimeException(
            "Backup not available. Call backup() before reset()."
        );
    }
}
